<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * DogovorAdminController class
 */
class DogovorAdminController extends SalesAdminController
{
    protected $filterNamespace = 'organization.dogovor.admin.filters';
    protected $baseRoute = 'lists_dogovor_admin_organization_index';
    protected $baseRoutePrefix = 'dogovor_admin';
    protected $baseTemplate = 'DogovorAdmin';

     /**
     * Executes new action
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {

        if ($this->getUser()->hasRole('ROLE_ADMIN')) {
            $form = $this->createForm('organizationDogovorAdminForm');
        } else {
            $form = $this->createForm('organizationSalesForm');
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();

            $user = $this->getUser();

            $organization->addUser($user);
            $organization->setCreator($user);

            $em = $this->getDoctrine()->getManager();

            $em->persist($organization);
            $em->flush();

            if ($organization->getLookup() && $organization->getLookup()->getId() == 61) {
                $this->baseRoutePrefix = 'competitors';
            }

            return $this->redirect($this->generateUrl('lists_' . $this->baseRoutePrefix . '_organization_show', array(
                        'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:' . $this->baseTemplate . ':new.html.twig', array(
                'filterForm' => $form->createView(),
                'filterFormName' => $this->filterFormName,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
