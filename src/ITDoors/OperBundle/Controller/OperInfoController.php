<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\DepartmentBundle\ListsDepartmentBundle;

/**
 * OperInfoController class
 *
 * Default controller for oper page
 */
class OperInfoController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.filter.namespace.oper.department.table';

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction()
    {
        return $this->render('ITDoorsOperBundle:Patterns:index.html.twig', array(

        ));

    }

    /**
     * departmentAction
     *
     * @return mixed[]
     */
    public function departmentAction()
    {

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);
        $this->clearPaginator($filterNamespace);

        $page = 1;
        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        /** @var  $repository \Lists\DepartmentBundle\Entity\DepartmentsRepository*/
        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $query = $repository->getFilteredDepartments($filters, $this->getAllowedDepartmentsId());

        $countDepartments = $repository
            ->getFilteredDepartments($filters, $this->getAllowedDepartmentsId(), "count")
            ->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $countDepartments);
        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array(
            'pagination' => $pagination,
        ));

    }

    /**
     * departmentTableAction
     *
     * @return mixed[]
     */
    public function departmentTableAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page=1;
        }

        /** @var  $departmentsRepository \Lists\DepartmentBundle\Entity\DepartmentsRepository*/
        $departmentsRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $query = $departmentsRepository->getFilteredDepartments($filters, $this->getAllowedDepartmentsId());

        $paginator  = $this->get('knp_paginator');

        $countDepartments = $departmentsRepository
            ->getFilteredDepartments($filters, $this->getAllowedDepartmentsId(), "count")
            ->getSingleScalarResult();

        $query->setHint('knp_paginator.count', $countDepartments);

        $pagination = $paginator->paginate(
            $query,
            $page,
            20
        );

        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @return array|bool
     */
    private function getAllowedDepartmentsId()
    {
        $idUser = $this->getUser()->getId();
        $checkOper =  $this->getUser()->hasRole('ROLE_OPER');

        $checkSuperviser =  $this->getUser()->hasRole('ROLE_SUPERVISOR');

        if ($checkSuperviser) {

            return false;
        } elseif ($checkOper) {

            /** @var  $stuff \SD\UserBundle\Entity\Stuff */
            $stuff = $this->getDoctrine()
                ->getRepository('SDUserBundle:Stuff')
                ->findOneBy(array('user' => $idUser));

            if (!$stuff) {
                return array();
            }


            $stuffDepartments = $this->getDoctrine()
                ->getRepository('SDUserBundle:StuffDepartments')
                ->findBy(array('stuff' => $stuff));

            if (count($stuffDepartments) == 0 || !$stuffDepartments) {
                return array();
            }

            if (!is_array($stuffDepartments)) {
                $stuffDepartments = array($stuffDepartments);
            }

            $idDepartmentsAllowed = array();

            /** @var  $stuffDepartment \SD\UserBundle\Entity\StuffDepartments */
            foreach ($stuffDepartments as $stuffDepartment) {
                $departmentsAllowed = $stuffDepartment->getDepartments();

                if (count($departmentsAllowed) == 0) {
                    return array();
                }
                if (!is_array($departmentsAllowed)) {
                    $departmentsAllowed = array($departmentsAllowed);
                }

                foreach ($departmentsAllowed as $departmentAllowed) {
                    $idDepartmentsAllowed[] = $departmentAllowed->getId();
                }
            }

            return $idDepartmentsAllowed;
        }
    }

    /**
     * updateAccrualsTask
     */
    public function updateAccrualsTaskAction()
    {
        for ($i=1; $i<=40; $i++) {
            $counter = $i*1000;
            $offset = ($i-1)*1000;

            $monthInfos = $this->getDoctrine()
                ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo')
                ->findBy(array(), array(), $counter, $offset);

            $em = $this->getDoctrine()->getManager();
            /** @var  $monthInfo \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfo */
            foreach ($monthInfos as $monthInfo) {

                $id = $monthInfo->getId();

                $fineDescription = $monthInfo->getFineDescription();
                if (!$fineDescription) {
                    $fineDescription = '';
                }

                $bonusDescription = $monthInfo->getBonusDescription();
                if (!$bonusDescription) {
                    $bonusDescription = '';
                }

                $surchargeDescription = $monthInfo->getSurchargeDescription();
                if (!$surchargeDescription) {
                    $surchargeDescription = '';
                }


                $fineValue = $monthInfo->getFine();
                if (!$fineValue) {
                    $fineValue = 0;
                }
                $bonusValue = $monthInfo->getBonus();
                if (!$bonusValue) {
                    $bonusValue = 0;
                }

                $surchargeValue = $monthInfo->getSurcharge();
                if (!$surchargeValue) {
                    $surchargeValue = 0;
                }


                $fineType = $monthInfo->getFineType();//a
                if ($fineType) {
                    $fineType = $fineType->getName();
                }
                $bonusType = $monthInfo->getBonusType();//b
                if ($bonusType) {
                    $bonusType = $bonusType->getName();
                }
                $surchargeType = $monthInfo->getSurchargeType();//c
                if ($surchargeType) {
                    $surchargeType = $surchargeType->getName();
                }

                $fineKey = $monthInfo->getFineTypeKey();//r,d,k
                $bonusKey = $monthInfo->getBonusTypeKey();
                $surchargeKey = $monthInfo->getSurchargeTypeKey();


                if ($fineType != null) {
                    $onceOnlyAccrual = new \Lists\DepartmentBundle\Entity\OnceOnlyAccrual();
                    $onceOnlyAccrual->setDepartmentPeopleMonthInfo($monthInfo);

                    $mpk = $monthInfo->getDepartmentPeople()->getMpks();

                    $onceOnlyAccrual->setMpk($mpk);
                    $onceOnlyAccrual->setType('fine');
                    $onceOnlyAccrual->setValue($fineValue);
                    $onceOnlyAccrual->setWorkType($fineKey);
                    $onceOnlyAccrual->setDescription($fineDescription);
                    $onceOnlyAccrual->setIsActive(true);

                    if (substr($fineType, 0, 1) == 'B') {
                        $code = 'uo';
                    } else {
                        $code = 'oz';
                    }
                    $onceOnlyAccrual->setCode($code);
                    $em->persist($onceOnlyAccrual);
                    $em->flush();
                }
                if ($bonusType != null) {
                    $onceOnlyAccrual = new \Lists\DepartmentBundle\Entity\OnceOnlyAccrual();
                    $onceOnlyAccrual->setDepartmentPeopleMonthInfo($monthInfo);

                    $mpk = $monthInfo->getDepartmentPeople()->getMpks();

                    $onceOnlyAccrual->setMpk($mpk);
                    $onceOnlyAccrual->setType('add');
                    $onceOnlyAccrual->setValue($bonusValue);
                    $onceOnlyAccrual->setWorkType($bonusKey);
                    $onceOnlyAccrual->setDescription($bonusDescription);
                    $onceOnlyAccrual->setIsActive(true);

                    if (substr($bonusType, 0, 1) == 'B') {
                        $code = 'uo';
                    } else {
                        $code = 'oz';
                    }
                    $onceOnlyAccrual->setCode($code);
                    $em->persist($onceOnlyAccrual);
                    $em->flush();
                }
                if ($surchargeType != null) {
                    $onceOnlyAccrual = new \Lists\DepartmentBundle\Entity\OnceOnlyAccrual();
                    $onceOnlyAccrual->setDepartmentPeopleMonthInfo($monthInfo);

                    $mpk = $monthInfo->getDepartmentPeople()->getMpks();

                    $onceOnlyAccrual->setMpk($mpk);
                    $onceOnlyAccrual->setType('add');
                    $onceOnlyAccrual->setValue($surchargeValue);
                    $onceOnlyAccrual->setWorkType($surchargeKey);
                    $onceOnlyAccrual->setDescription($surchargeDescription);
                    $onceOnlyAccrual->setIsActive(true);

                    if (substr($surchargeType, 0, 1) == 'B') {
                        $code = 'uo';
                    } else {
                        $code = 'oz';
                    }
                    $onceOnlyAccrual->setCode($code);
                    $em->persist($onceOnlyAccrual);
                    $em->flush();
                }

            }
        }


    }
}
