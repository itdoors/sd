<?php

namespace Lists\OrganizationBundle\Services;

use Doctrine\ORM\EntityManager;
use Lists\HandlingBundle\Entity\HandlingService;
use Lists\HandlingBundle\Entity\HandlingServiceRepository;
use Lists\OrganizationBundle\Entity\OrganizationRepository;
use Lists\OrganizationBundle\Entity\OrganizationServiceCover;
use Symfony\Component\DependencyInjection\Container;
use Lists\LookupBundle\Entity\Lookup;
use SD\UserBundle\Entity\User;
use Lists\OrganizationBundle\Entity\Organization;
use Lists\OrganizationBundle\Entity\OrganizationUser;
use ITDoors\HistoryBundle\Entity\History;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * OrganizationServiceCoverService class
 */
class OrganizationServiceCoverService
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function addFormDefaults(Form $form, $defaults)
    {

    }

    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveForm(Form $form, Request $request, $params)
    {
        $data = $form->getData();

        /** @var OrganizationServiceCover $organizationServiceCover */
        $organizationServiceCover = new OrganizationServiceCover();

        /** @var OrganizationRepository $or */
        $or = $this->container->get('organization.repository');
        /** @var HandlingServiceRepository $hr */
        $hsr = $this->container->get('handling.service.repository');

        /** @var Organization $organization */
        $organization = $or->find($data['organizationId']);
        /** @var HandlingService $handlingService */
        $handlingService = $hsr->find($data['serviceId']);

        $organizationServiceCover->setOrganization($organization);
        $organizationServiceCover->setService($handlingService);
        $organizationServiceCover->setIsInterested($data['isInterested'] ? $data['isInterested'] : false);
        $organizationServiceCover->setIsWorking($data['isWorking'] ? $data['isWorking'] : false);
        $organizationServiceCover->setResponsible($data['responsible']);
        $organizationServiceCover->setDescription($data['description']);
        $organizationServiceCover->setEvaluation($data['evaluation']);
        $organizationServiceCover->setEndDate($data['endDate']);

        if ($data['competitorId']) {
            /** @var Organization $competitor */
            $competitor = $or->find($data['competitorId']);
            $organizationServiceCover->setCompetitor($competitor);
        }

        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();
        $organizationServiceCover->setCreator($user);

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $em->persist($organizationServiceCover);
        $em->flush();
    }
}
