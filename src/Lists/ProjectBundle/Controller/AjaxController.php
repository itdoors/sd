<?php

namespace Lists\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Lists\ProjectBundle\Entity\FileProject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Lists\ProjectBundle\Entity\ProjectStateTender;
use Lists\ProjectBundle\Entity\ServiceProjectStateTender;

/**
 * Class AjaxController
 */
class AjaxController extends Controller
{
    
    /**
     * removeManagerAction
     * 
     * @param integer $id
     * 
     * @return Response
     */
    public function removeManagerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $return = array('error'=>false);

        $manager = $this->getDoctrine()->getRepository('ListsProjectBundle:Manager')->find($id);
         if (!$manager) {
            $return['error'] = 'Manager doesn`t found';

            return new Response(json_encode($return));
        }
        $project = $manager->getProject();
        $service = $this->get('lists_project.service');
        $access= $service->checkAccess($this->getUser(), $project);
        if (!$access->canChangeManager()) {
            throw $this->createAccessDeniedException();
        }
        $managers = $project->getManagers();
        foreach ($managers as $managerTemp) {
            if ($managerTemp->isManagerProject()) {
                $managerTemp->plusPart($manager->getPart());
                $em->persist($managerTemp);
                $em->remove($manager);
            }
        }
       
        
        $em->flush();

        return new Response(json_encode($return));
    }
    /**
     * editableProjectAction
     * 
     * @return Response
     * @throws \Exception
     */
    public function editableProjectAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        $object = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Project')
            ->find($pk);
        $temp = explode('\\', get_class($object));
        $className = end($temp);
        $service = $this->get('lists_project.service');
        $access= $service->checkAccess($this->getUser(), $object);
        $method = 'canEdit'.$className;
        if (!$access->$method()) {
            throw $this->createAccessDeniedException();
        }
        if (in_array($name, array('datetimeDeadline', 'datetimeOpening'))) {
            if (!empty($value)) {
                $value = new \DateTime($value);
            } else {
                $value = null;
            }
        }
        if (in_array($name, array('square', 'pf'))) {
            if (!empty($value)) {
                $value = str_replace(',', '.', str_replace(' ', '', $value));
            } else {
                $value = null;
            }
        }
        if ($name == 'status') {
            $value = $this->getDoctrine()
                    ->getRepository('ListsProjectBundle:Status')
                    ->find(array('id' => (int) $value));
        }
        if ($name == 'kveds') {
            $kvedsOld = $object->getKveds();
            foreach ($kvedsOld as $kved) {
                $object->removeKved($kved);
            }
            $kveds = $this->getDoctrine()
                    ->getRepository('ListsOrganizationBundle:Kved')
                    ->findBy(array('id' => $value));
            foreach ($kveds as $kved) {
                $object->addKved($kved);
            }
        } elseif ($name == 'services') {
            $servicesOld = $object->getServices();
            foreach ($servicesOld as $service) {
                $object->removeService($service);
            }
            /** @var ServiceProjectStateTender[] $services */
            $services = $this->getDoctrine()
                    ->getRepository('ListsProjectBundle:Service')
                    ->findBy(array('id' => $value));
            foreach ($services as $service) {
                $object->addService($service);
            }
        } else {
            $object->$methodSet($value);
        }

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors) ) {
            foreach ($errors as $error){
                if ($error->getPropertyPath() == $name) {
                    return new Response($error->getMessage(), 406);
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        $return = array('error'=>false);

        $return['value'] = $value;
        $return['method'] = $methodSet;
        $return['object'] = $object;
        try {
            $em->flush();
            $em->refresh($object);
        } catch (\ErrorException $e) {
            $return['error'] = $e->getMessage();
        }

        return new Response(json_encode($return));
    }
    /**
     * servicesProjectStateTenderSearchAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function servicesProjectStateTenderSearchAction(Request $request)
    {
        $searchText = $request->query->get('query');

        /** @var \Lists\ProjectBundle\Entity\ServiceProjectStateTenderRepository $serviceRepository */
        $serviceRepository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:ServiceProjectStateTender');

        /** @var ServiceProjectStateTender[] $services */
        $services = $serviceRepository->getSearchQuery($searchText);

        $result = array();

        foreach ($services as $service) {
            $id = $service->getId();
            $string = $service->getName();

            $result[] = array(
                'id' => $id,
                'value' => $id,
                'name' => $string,
                'text' => $string
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * servicesProjectSimpleSearchAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function servicesProjectSimpleSearchAction(Request $request)
    {
        $searchText = $request->query->get('query');

        $services = $this->getDoctrine()->getRepository('ListsProjectBundle:ServiceProjectSimple')
            ->getSearchQuery($searchText);

        $result = array();

        foreach ($services as $service) {
            $id = $service->getId();
            $string = $service->getName();

            $result[] = array(
                'id' => $id,
                'value' => $id,
                'name' => $string,
                'text' => $string
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * searchStatusProjectStateTenderAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function searchStatusProjectStateTenderAction(Request $request)
    {
        $searchText = $request->query->get('query');
        /** @var \Lists\ProjectBundle\Entity\StatusProjectStateTenderRepository[] $status */
        $status = $this->getDoctrine()->getRepository('ListsProjectBundle:StatusProjectStateTender')
            ->getSearchQuery($searchText);
        $result = array();
        foreach ($status as $val) {
            $id = $val->getId();
            $string = $val->getName();
            $result[] = array(
                'id' => $id,
                'value' => $id,
                'name' => $string,
                'text' => $string
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * searchStatusProjectStateTenderAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function searchStatusSimpleAction(Request $request)
    {
        $searchText = $request->query->get('query');
        /** @var \Lists\ProjectBundle\Entity\StatusProjectSimpleRepository[] $status */
        $status = $this->getDoctrine()->getRepository('ListsProjectBundle:StatusProjectSimple')
            ->getSearchQuery($searchText);
        $result = array();
        foreach ($status as $val) {
            $id = $val->getId();
            $string = $val->getName();
            $result[] = array(
                'id' => $id,
                'value' => $id,
                'name' => $string,
                'text' => $string
            );
        }

        return new Response(json_encode($result));
    }
    /**
     * projectServicesByIdsAction
     *
     * @return string
     */
    public function projectServicesByIdsAction()
    {
        $ids = explode(',', $this->get('request')->query->get('ids'));

        /** @var \Lists\ProjectBundle\Entity\ServiceRepository[] $services */
        $services = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Service')->getGyIds($ids);

        $result = array();

        foreach ($services as $service) {
            $id = $service->getId();
            $string = $service->getName();

            $result[] = array(
                'id' => $id,
                'value' => $id,
                'name' => $string,
                'text' => $string
            );
        }

        return new Response(json_encode($result));
    }
     /**
     * editableProjectStateTenderParticipantAction
     * 
     * @return Response
     * @throws \Exception
     */
    public function editableProjectStateTenderParticipantAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        /** @var ProjectGosTenderParticipan $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:ProjectStateTenderParticipant')
            ->find($pk);
        $service = $this->get('lists_project.service');
        $access= $service->checkAccess($this->getUser(), $object->getProjectStateTender());
        if (!$access->canEditProjectStateTender()) {
            throw $this->createAccessDeniedException();
        }
        if ($name == 'summa') {
            $value = str_replace(',', '.', str_replace(' ', '', $value));
        }

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors) ) {
            foreach ($errors as $error){
                if ($error->getPropertyPath() == $name) {
                    return new Response($error->getMessage(), 406);
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        $return = array('error'=>false);

        $return['value'] = $value;
        $return['method'] = $methodSet;
        $return['object'] = $object;
        try {
            $em->flush();
            $em->refresh($object);
        } catch (\ErrorException $e) {
            $return['error'] = $e->getMessage();
        }

        return new Response(json_encode($return));
    }
    /**
     * editableProjectFileAction
     * 
     * @return Response
     * @throws \Exception
     */
    public function editableProjectFileAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        /** @var FileProject $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:FileProject')
            ->find($pk);
        $service = $this->get('lists_project.service');
        $access= $service->checkAccess($this->getUser(), $object->getProject());
        if (!$access->canEditProjectStateTender()) {
            throw new \Exception('No access', 403);
        }
        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors) ) {
            foreach ($errors as $error){
                if ($error->getPropertyPath() == $name) {
                    return new Response($error->getMessage(), 406);
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        $return = array('error'=>false);

        $return['value'] = $value;
        $return['method'] = $methodSet;
        $return['object'] = $object;
        try {
            $em->flush();
            $em->refresh($object);
        } catch (\ErrorException $e) {
            $return['error'] = $e->getMessage();
        }

        return new Response(json_encode($return));
    }
    /**
     * messageUploadAction
     * 
     * @param integer $id
     * @param Request $request
     * 
     * @return Response
     * @throws \Exception
     */
    public function messageUploadAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $object = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:Message')
            ->find($id);
        $eventDatetime = $request->request->get('eventDatetime');
        if ($eventDatetime && !empty($eventDatetime)) {
            $eventDatetime = new \DateTime($eventDatetime);
            $object->setEventDatetime($eventDatetime);
        }
        

//        $service = $this->get('lists_project.service');
//        $access= $service->checkAccess($this->getUser(), $object->getProject());
//        if (!$access->canEditMessage()) {
//            throw new \Exception('No access', 403);
//        }

        $validator = $this->get('validator');
        $return = array('error'=>false);
        

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors) ) {
            foreach ($errors as $error){
                if ($error->getPropertyPath() == 'eventDatetime') {
                    $em->refresh($object);
                    $return['error'] = $error->getMessage();
                    $return['eventDatetime'] = $object->getEventDatetime()->format('Y-m-d H:i:s');
 
                    return new Response(json_encode($return));
                }
            }
        }

        
        $em->persist($object);

        $return['eventDatetime'] = $object->getEventDatetime()->format('Y-m-d H:i:s');
        try {
            $em->flush();
            $em->refresh($object);
        } catch (\ErrorException $e) {
            $return['error'] = $e->getMessage();
        }

        return new Response(json_encode($return));
    }
    /**
     * Saves project file ajax
     *
     * @param integer $id
     * @param Request $request
     *
     * @return boolean
     */
    public function uploadFileAction($id, Request $request)
    {
        $result = array();
        $em = $this->getDoctrine()->getManager();
        $projectFile = $em
            ->getRepository('ListsProjectBundle:File')
            ->find($id);

        if (!$projectFile) {
            throw $this->createNotFoundException();
        }
        $file = $request->files->get('file');

        if ($file) {
            $fileValidator = new File();
            $fileValidator->maxSize = '5M';
            $fileValidator->mimeTypes = array(
                'application/pdf',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint'
            );
            $validator = $this->get('validator');

            /** @var \Symfony\Component\Validator\ConstraintViolationList $errors*/
            $errors = $validator->validate($file, $fileValidator);

            if (sizeof($errors)) {
                foreach ($errors as $error){
                    return new Response($error->getMessage(), 406);
                }

            }
            $projectFile->setFile($file);
            $projectFile->upload();
            $projectFile->setUser($this->getUser());
            $em->persist($projectFile);
            $em->flush();
            
            $result['id'] = $projectFile->getId();
            $result['file'] = $this->generateUrl('it_doors_file_access_get_if_authenticated', array(
                'path' => $projectFile->getWebPath(),
                'timestamp' => time()
            ));
        } else {
            $result['error'] = 'File not found';
        }

        return new Response(json_encode($result));
    }
}
