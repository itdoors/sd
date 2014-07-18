<?php

namespace Lists\HandlingBundle\Services;

use Doctrine\ORM\EntityManager;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingUser;
use Lists\HandlingBundle\Entity\HandlingRepository;
use Symfony\Component\DependencyInjection\Container;
use Lists\LookupBundle\Entity\Lookup;
use SD\UserBundle\Entity\User;

/**
 * HandlingUserService class
 */
class HandlingUserService
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
     * Save form
     *
     * @param integer $organizationId
     * @param integer $userId
     */
    public function changeManagerProject($organizationId, $userId)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var Handling $handlings */
        $handlings = $em->getRepository('ListsHandlingBundle:Handling')
                ->findBy(array('organization_id' => $organizationId));
        /** @var Lookup $lookup */
         $lookup = $em->getRepository('ListsLookupBundle:Lookup')->findOneBy(array('lukey' => 'manager_organization'));
        /** @var User $user */
         $user = $em->getRepository('SDUserBundle:User')->find($userId);

        foreach ($handlings as $h) {
            $handlingUser = $em->getRepository('ListsHandlingBundle:HandlingUser')
                ->findOneBy(array('handlingId' => $h->getId()));
            if (!$handlingUser) {
                $handlingUser = new HandlingUser();
                $handlingUser->setHandling($h);
                $handlingUser->setLookup($lookup);
                $handlingUser->setPart(100);
            }
            $handlingUser->setUser($user);
            $em->persist($handlingUser);
        }
        $em->flush();
    }
}
