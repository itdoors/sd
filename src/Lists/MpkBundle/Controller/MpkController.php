<?php

namespace Lists\MpkBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;

/**
 * MpkController class
 *
 * Default controller for oper page
 */
class MpkController extends BaseFilterController
{
    /**
     * modalAction
     * 
     * @param integer $departmentId
     * 
     * @return type
     */
    public function modalAction ($departmentId)
    {
        return $this->render('ListsMpkBundle:Mpk:modal.html.twig', array (
            'departmentId' => $departmentId
        ));
    }
    /**
     * listAction
     * 
     * @param integer $departmentId
     * 
     * @return type
     */
    public function listAction ($departmentId)
    {
        $em = $this->getDoctrine()->getManager();
        $mpk = $em->getRepository('ListsMpkBundle:Mpk')->getMpkForDepartment($departmentId);

        return $this->render('ListsMpkBundle:Mpk:list.html.twig', array (
            'mpk' => $mpk
        ));
    }
    /**
     * saveAction
     * 
     * @return type
     */
    public function saveAction ()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');
        $value = trim($value);
        $methodSet = 'set' . ucfirst($name);

        $mpk = $this->getDoctrine()
            ->getRepository('ListsMpkBundle:Mpk')
            ->find($pk);
        if ($name == 'organization') {
            $value = $this->getDoctrine()
                ->getRepository('ListsOrganizationBundle:Organization')
                ->find((int) $value);
        } elseif (in_array($name,  array('startDate', 'endDate'))) {
            if (empty($value)) {
                $value = null;
            } else {
                $value = new \DateTime($value);
            }
        }
        $mpk->$methodSet($value);
        $validator = $this->get('validator');
        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($mpk);

        if (sizeof($errors)) {
            $return = $errors[0]->getMessage();

            return new Response($return, 406);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($mpk);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }
}
