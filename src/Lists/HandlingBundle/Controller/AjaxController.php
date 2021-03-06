<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Lists\HandlingBundle\Entity\ProjectGosTender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class AjaxController
 */
class AjaxController extends Controller
{
    /**
     * handlingServicesSearchAction
     * 
     * @param Request $request
     *
     * @return string
     */
    public function handlingServicesSearchAction(Request $request)
    {
        $searchText = $request->query->get('query');

        /** @var \Lists\HandlingBundle\Entity\HandlingServicesRepository $handlingServicesRepository */
        $handlingServicesRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService');

        /** @var HandlingServices[] $handlingServices */
        $handlingServices = $handlingServicesRepository->getGosTenderSearchQuery($searchText);

        $result = array();

        foreach ($handlingServices as $service) {
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
     * handlingServicesByIds
     *
     * @return string
     */
    public function handlingServicesByIdsAction()
    {
        $ids = explode(',', $this->get('request')->query->get('ids'));

        /** @var \Lists\HandlingBundle\Entity\HandlingServicesRepository $handlingServicesRepository */
        $handlingServicesRepository = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:HandlingService');

        /** @var HandlingServices[] $handlingServices */
        $handlingServices = $handlingServicesRepository
            ->createQueryBuilder('hs')
            ->where('hs.id in (:ids)')
            ->setParameter(':ids', $ids)
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($handlingServices as $service) {
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
     * editableGosTenderAction
     * 
     * @return Response
     * @throws \Exception
     */
    public function editableGosTenderAction()
    {
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);

        /** @var ProjectGosTender $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:ProjectGosTender')
            ->find($pk);
        $service = $this->get('lists_handling.service');
        $access= $service->checkAccess($this->getUser(), $object->getProject());
        if (!$access->canEditGosTender()) {
            throw $this->createAccessDeniedException();
        }
        if (in_array($name, array('datetimeDeadline', 'datetimeOpening'))) {
            if (!empty($value)) {
                $value = new \DateTime($value);
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
