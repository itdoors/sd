<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Lists\HandlingBundle\Entity\ProjectGosTender;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AjaxController
 */
class AjaxController extends Controller
{
    public function editableGosTenderAction()
    {
        $service = $this->get('lists_handling.service');
        $access= $service->checkAccess($this->getUser());
        if (!$access->canEdit()) {
            throw new \Exception('No access', 403);
        }
        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');

        $methodSet = 'set' . ucfirst($name);


        /** @var ProjectGosTender $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsHandlingBundle:ProjectGosTender')
            ->find($pk);

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
        $project = $em
            ->getRepository('ListsHandlingBundle:Handling')
            ->find($id);

        if (!$project) {
            throw $this->createNotFoundException();
        }
        $file = $request->files->get('file');

        if ($file) {
            $projectFile = new \Lists\HandlingBundle\Entity\ProjectFile();
            $projectFile->setProject($project);
            $projectFile->setFile($file);
            $projectFile->upload();
            $result['file'] = $projectFile->getWebPath();
            $result['id'] = $projectFile->getId();
            $em->persist($projectFile);
        } else {
            $result['error'] = 'File not found';
        }
        $em->flush();

        return new Response(json_encode($result));
    }
}
