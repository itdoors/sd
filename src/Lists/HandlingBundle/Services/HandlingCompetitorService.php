<?php

namespace Lists\HandlingBundle\Services;

use Doctrine\ORM\EntityManager;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingCompetitor;
use Lists\HandlingBundle\Entity\HandlingRepository;
use Lists\OrganizationBundle\Entity\Organization;
use Lists\OrganizationBundle\Entity\OrganizationRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * HandlingCompetitiorService class
 */
class HandlingCompetitorService
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

        $handlingCompetitor = new HandlingCompetitor();

        /** @var OrganizationRepository $or */
        $or = $this->container->get('organization.repository');
        /** @var HandlingRepository $hr */
        $hr = $this->container->get('handling.repository');

        /** @var Organization $competitor */
        $competitor = $or->find($data['competitorId']);
        /** @var Handling $handling */
        $handling = $hr->find($data['handlingId']);

        $handlingCompetitor->setCompetitor($competitor);
        $handlingCompetitor->setStrengths($data['strengths']);
        $handlingCompetitor->setWeaknesses($data['weaknesses']);
        $handlingCompetitor->setHandling($handling);
        $handlingCompetitor->setEndDate($data['endDate']);
        $handlingCompetitor->setTotal($data['total']);

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $em->persist($handlingCompetitor);
        $em->flush();
    }
}
