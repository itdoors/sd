<?php

namespace Lists\ContactBundle\Controller;

class SalesDispatcherController extends SalesController
{
    protected $baseRoutePrefix = 'sales_dispatcher';
    protected $baseTemplate = 'SalesDispatcher';

    public function organizationAction($organizationId)
    {
        /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        $user = $this->getUser();

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        $organizationContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyOrganizationsContacts($teamUserIds, $organizationId)
            ->getResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':organization.html.twig', array(
                'organizationContacts' => $organizationContacts,
                'organizationId' => $organizationId,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
            ));
    }

    public function handlingAction($handlingId)
    {
        /** @var \Lists\TeamBundle\Entity\TeamRepository $teamRepository */
        $teamRepository = $this->get('lists_team.repository');

        $user = $this->getUser();

        $teamUserIds = $teamRepository->getMyTeamIdsByUser($user);

        $handlingContacts = $this->getDoctrine()->getRepository('ListsContactBundle:ModelContact')
            ->getMyHandlingContacts($teamUserIds, $handlingId)
            ->getQuery()
            ->getResult();

        return $this->render('ListsContactBundle:' . $this->baseTemplate . ':handling.html.twig', array(
                'handlingContacts' => $handlingContacts,
                'handlingId' => $handlingId,
                'baseTemplate' => $this->baseTemplate,
                'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }
}
