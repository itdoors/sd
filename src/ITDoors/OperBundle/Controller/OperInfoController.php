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
}
