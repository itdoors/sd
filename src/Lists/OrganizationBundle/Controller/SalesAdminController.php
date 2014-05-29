<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class SalesAdminController
 */
class SalesAdminController extends SalesDispatcherController
{
    protected $filterNamespace = 'organization.sales.admin.filters';
    protected $filterFormName = 'organizationSalesAdminFilterForm';
    protected $baseRoute = 'lists_sales_admin_organization_index';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);

        $filterForm = $this->processFilters();

        /** @var \SD\UserBundle\Entity\User $user*/
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        /** @var \Doctrine\ORM\Query $organizationsQuery */
        $organizationsQuery = $organizationsRepository->getAllForSalesQuery(null, $this->getFilters());

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $organizationsQuery,
            $page,
            20
        );

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':index.html.twig', array(
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
            'filterFormName' => $this->filterFormName,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
