<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;

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
        var_dump($this->getAllowedDepartmentsId());die();
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

        $query = $repository->getFilteredDepartments($filters);

        $countDepartments = $repository->getFilteredDepartments($filters, "count")->getSingleScalarResult();

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

        $query = $departmentsRepository->getFilteredDepartments($filters);

        $paginator  = $this->get('knp_paginator');

        $countDepartments = $departmentsRepository->getFilteredDepartments($filters, "count")->getSingleScalarResult();

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

        $checkSuperviser =  $this->getUser()->hasRole('ROLE_SUPERVISER');

        if ($checkSuperviser) {

            return false;
        } elseif ($checkOper) {

            /** @var  $stuff \SD\UserBundle\Entity\Staff */
            $stuff = $this->getDoctrine()
                ->getRepository('SDUserBundle:Stuff')
                ->findBy(array('user' => $idUser));


            $stuffDepartments = $this->getDoctrine()
                ->getRepository('SDUserBundle:Stuff')
                ->findBy(array('stuff' => $stuff));
            $idDepartmentsAllowed = array();
            /** @var  $stuffDepartment \SD\UserBundle\Entity\StuffDepartments */
            foreach ($stuffDepartments as $stuffDepartment) {
                $departmentsAllowed = $stuffDepartment->getDepartments();
                foreach ($departmentsAllowed as $departmentAllowed) {
                    $idDepartmentsAllowed[] = $departmentAllowed->getId();
                }
            }

            return $idDepartmentsAllowed;
        }
    }
}
