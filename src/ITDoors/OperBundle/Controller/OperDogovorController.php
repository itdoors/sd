<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * OperScheduleController class
 *
 * Default controller for oper schedule
 */
class OperDogovorController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.namespace.oper.department.dogovor';

    /**
     * indexAction
     *
     * @param integer $id
     *
     * @return mixed[]
     */

    public function indexAction($id)
    {
        $idDepartment = $id;

        /** @var  $dogovorDepartmentRepository \Lists\DogovorBundle\Entity\DogovorDepartmentRepository */
        $dogovorDepartmentRepository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DogovorDepartment');

        $dogovorsDepartment = $dogovorDepartmentRepository->findBy(array(
            'department' => $idDepartment,
        ));
        /*
        $infoDogovors = array();

        /** @var  $dogovorDepartment \Lists\DogovorBundle\Entity\DogovorDepartment
        foreach ($dogovors as $dogovorDepartment) {
            if ($dogovorDepartment->getDopDogovor() != null) {
                $dogovorKey = 'dogovor_'.$dogovorDepartment->getId();
                /** @var  $dogovor \Lists\DogovorBundle\Entity\Dogovor
                $dogovor = $dogovorDepartment->getDogovor();
                $infoDogovors[$dogovorKey]['id'] = $dogovor->getId();
                $infoDogovors[$dogovorKey]['prolongation'] = $dogovor->getProlongation();
                $infoDogovors[$dogovorKey]['organization'] = $dogovor->getOrganization()->getName();
                $infoDogovors[$dogovorKey]['name'] = $dogovor->getName();
                $infoDogovors[$dogovorKey]['number'] = $dogovor->getNumber();
                $infoDogovors[$dogovorKey]['dateStart'] = $dogovor->getStartdatetime();
                $infoDogovors[$dogovorKey]['dateEnd'] = $dogovor->getStopdatetime();
                $infoDogovors[$dogovorKey]['city'] = $dogovor->getCity();
                $infoDogovors[$dogovorKey]['subject'] = $dogovor->getSubject();
                $infoDogovors[$dogovorKey]['status'] = $dogovor->getIsActive();
                $infoDogovors[$dogovorKey]['type'] = $dogovor->getDogovorType()->getName();
                $infoDogovors[$dogovorKey]['personCreated'] = $dogovor->getUser();
                $infoDogovors[$dogovorKey]['personCreated'] = $dogovor->getUser();
                $infoDogovors[$dogovorKey]['personCreated'] = $dogovor->getUser();

            }
        }
    */



        return $this->render('ITDoorsOperBundle:Dogovor:dogovorMain.html.twig', array(
            'dogovorsDepartment' => $dogovorsDepartment
        ));
    }

}