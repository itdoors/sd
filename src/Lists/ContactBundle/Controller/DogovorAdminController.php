<?php

namespace Lists\ContactBundle\Controller;

/**
 * Class SalesAdminController
 */
class DogovorAdminController extends SalesController
{
    protected $filterNamespace = 'contacts.dogovor.admin.filters';
    protected $baseRoutePrefix = 'dogovor_admin';
    protected $baseTemplate = 'DogovorAdmin';

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

        return $this->render('ListsContactBundle:SalesAdmin:organization.html.twig', array(
                'pagination' => $pagination,
                'organizationId' => $organizationId,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
            ));
    }
}
