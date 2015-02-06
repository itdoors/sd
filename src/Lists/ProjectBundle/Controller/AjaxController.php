<?php

namespace Lists\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Lists\HandlingBundle\Entity\ProjectGosTender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Lists\ProjectBundle\Entity\StateTender;
use Lists\ProjectBundle\Entity\ServiceStateTender;

/**
 * Class AjaxController
 */
class AjaxController extends Controller
{
    /**
     * editableGosTenderAction
     * 
     * @return Response
     * @throws \Exception
     */
    public function editableStateTenderAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        /** @var StateTender $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:StateTender')
            ->find($pk);
        $service = $this->get('lists_project.service');
        $access= $service->checkAccess($this->getUser(), $object);
        if (!$access->canEditStateTender()) {
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
            /** @var ServiceStateTender[] $services */
            $services = $this->getDoctrine()
                    ->getRepository('ListsProjectBundle:ServiceStateTender')
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
     * servicesStateTenderSearchAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function servicesStateTenderSearchAction(Request $request)
    {
        $searchText = $request->query->get('query');

        /** @var \Lists\ProjectBundle\Entity\ServiceStateTenderRepository $serviceRepository */
        $serviceRepository = $this->getDoctrine()
            ->getRepository('ListsProjectBundle:ServiceStateTender');

        /** @var ServiceStateTender[] $services */
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
     * searchStatusStateTenderAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function searchStatusStateTenderAction(Request $request)
    {
        $searchText = $request->query->get('query');
        /** @var \Lists\ProjectBundle\Entity\StatusStateTenderRepository[] $status */
        $status = $this->getDoctrine()->getRepository('ListsProjectBundle:StatusStateTender')
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
     * editableGosTenderParticipantAction
     * 
     * @return Response
     * @throws \Exception
     */
    public function editableGosTenderParticipantAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        /** @var ProjectGosTenderParticipan $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:ProjectGosTenderParticipan')
            ->find($pk);
        $service = $this->get('lists_handling.service');
        $access= $service->checkAccess($this->getUser(), $object->getGosTender()->getProject());
        if (!$access->canEditGosTender()) {
            throw $this->createAccessDeniedException();
        }
        if ($name == 'summa') {
            $value = str_replace(',', '.', $value);
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

        /** @var ProjectGosTender $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:ProjectFile')
            ->find($pk);
        $service = $this->get('lists_handling.service');
        $access= $service->checkAccess($this->getUser(), $object->getProject());
        if (!$access->canEditGosTender()) {
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
            ->getRepository('ListsHandlingBundle:ProjectFile')
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
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
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
