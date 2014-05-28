<?php

namespace Lists\ContactBundle\Controller;

/**
 * Class SalesAdminController
 */
class SalesAdminController extends SalesController
{
    protected $filterNamespace = 'contacts.sales.admin.filters';
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    /**
     * @param int $organizationId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function organizationAction($organizationId)
    {
        $this->refreshFiltersIfAjax();
        $page = $this->getFilterValueByKey('page', 1);

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts(null, $organizationId);

        if (!$organizationId) {
            /** @var \Knp\Component\Pager\Paginator $paginator */
            $paginator  = $this->get('knp_paginator');

            $pagination = $paginator->paginate(
                $organizationContacts,
                $page,
                20
            );
        } else {
            $pagination = $organizationContacts->getResult();
        }

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
                'pagination' => $pagination,
                'organizationId' => $organizationId,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
            ));
    }
}
