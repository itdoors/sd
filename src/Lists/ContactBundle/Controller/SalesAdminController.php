<?php

namespace Lists\ContactBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class SalesAdminController extends SalesController
{
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    public function organizationAction($organizationId)
    {
        $page = $this->get('request')->query->get('page', 1);

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts(null, $organizationId);

        if (!$organizationId)
        {
            /** @var \Knp\Component\Pager\Paginator $paginator */
            $paginator  = $this->get('knp_paginator');

            $pagination = $paginator->paginate(
                $organizationContacts,
                $page,
                20
            );
        }
        else
        {
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
