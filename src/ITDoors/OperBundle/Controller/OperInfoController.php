<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController;

/**
 * OperInfoController class
 *
 * Default controller for oper page
 */
class OperInfoController extends BaseFilterController
{
    protected $filterNamespace = 'report.sales.admin.filters';
    protected $filterFormName = 'reportSalesAdminFilterForm';

    /**
     * indexAction
     *
     * @return mixed[]
     */

    public function indexAction()
    {

        $page = $this->get('request')->query->get('page', 1);
        $this->addToFilters('page', $page);

        return $this->render('ITDoorsOperBundle:Default:index.html.twig', array(

        ));
    }

    /**
     * departmentAction
     *
     * @return mixed[]
     */

    public function departmentAction()
    {

        //$this->refreshFiltersIfAjax();
        $page = $this->getFilterValueByKey('page', 1);

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        /*
          $departments = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->findAll();
        */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $query = $repository->createQueryBuilder('p')
            ->leftJoin('p.city', 'region')
            ->getQuery();

        $departments = $query->getResult();

        $pagination = $paginator->paginate(
            $departments,
            $page,
            20
        );


        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * departmentTableAction
     *
     * @return mixed[]
     */

    public function departmentTableAction()
    {
        $filterNamespace = $this->container->getParameter('ajax.filter.namespace.oper.department.table');

        $filters = $this->getFilters($filterNamespace);

        $page = $this->getFilterValueByKey('page', 1);

        $departments = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->findById(1);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $departments,
            $page,
            20
        );

        return $this->render('ITDoorsOperBundle:Parts:department.html.twig', array(
            'pagination' => $pagination
        ));
    }
}
