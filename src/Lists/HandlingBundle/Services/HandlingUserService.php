<?php

namespace Lists\HandlingBundle\Services;

use Doctrine\ORM\EntityManager;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingUser;
use Lists\HandlingBundle\Entity\HandlingRepository;
use Symfony\Component\DependencyInjection\Container;
use Lists\LookupBundle\Entity\Lookup;
use SD\UserBundle\Entity\User;
use ITDoors\HistoryBundle\Entity\History;

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
        foreach ($handlings as $handling) {
            $this->changeManagerProjectOne($handling->getId(), $userId);
        }
    }
    /**
     * Save form
     *
     * @param integer $handlingId
     * @param integer $userId
     */
    public function changeManagerProjectOne($handlingId, $userId)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        /** @var User $authUser */
        $authUser = $this->container->get('security.context')->getToken()->getUser();

        $user = $em->getRepository('SDUserBundle:User')->find($userId);
        $handling = $em->getRepository('ListsHandlingBundle:Handling')->find($handlingId);

        $lookup = $em->getRepository('ListsLookupBundle:lookup')->findOneBy(array('lukey' => 'manager_project'));

        $mainManager = $em
            ->getRepository('ListsHandlingBundle:HandlingUser')
            ->findOneBy(array(
                'handlingId' => $handlingId,
                'lookupId' => $lookup->getId(),
                ));

        $part = 100;
        if (!$mainManager) {
            $mainManager = $em
                ->getRepository('ListsHandlingBundle:HandlingUser')
                ->findOneBy(array(
                    'handlingId' => $handlingId,
                    'userId' => $userId,
                ));
            if (!$mainManager) {
                $mainManager = new HandlingUser();
                $mainManager->setUser($user);
                $mainManager->setHandling($handling);
                $mainManager->setPart($part);
                $mainManager->setLookup($lookup);
            } else {
                $mainManager->setLookup($lookup);
                $mainManager->setPart($part);
            }
        } else {
            $oldManager = $em
                ->getRepository('ListsHandlingBundle:HandlingUser')
                ->findOneBy(array(
                    'handlingId' => $handlingId,
                    'userId' => $userId,
                    ));
            if ($oldManager) {
                $part = $mainManager->getPart()+$oldManager->getPart();
                $em->remove($oldManager);
                if ($part > 100) {
                    $part = 100;
                }
                $mainManager->setPart($part);
            } else {
                if ($mainManager->getPart() === null) {
                    $mainManager->setPart(100);
                }
            }

            $this->sendEmailRemoveManagerProject($handlingId, $mainManager->getUser()->getEmail(), $authUser);

            $history = new History();
            $history->setCreatedatetime(new \DateTime());
            $history->setFieldName('user_id');
            $history->setModelName('handling_user');
            $history->setModelId($mainManager->getId());
            $history->setUser($authUser);
            $history->setOldValue($mainManager->getUserId());
            $history->setValue($user->getId());
            $em->persist($history);

            $mainManager->setUser($user);
        }
        $this->sendEmailAddManagerProject($handlingId, $user->getEmail(), $authUser, $part);

        $em->persist($mainManager);
        $em->flush();

        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();
    }
    /**
     * sendEmailAddManagerProject
     * 
     * @param integer $handlingId
     * @param string  $email
     * @param object  $user
     * @param integer $part
     */
    private function sendEmailAddManagerProject($handlingId, $email, $user, $part)
    {
        $emailService = $this->container->get('it_doors_email.service');
        $url = $this->container->get('router')->generate(
            'lists_handling_show',
            array('id' => $handlingId, type => 'my'),
            true
        );
        $emailService->send(
            null,
            'add-manager-project',
            array(
                'users' => array(
                    $email
                ),
                'variables' => array(
                    '${lastName}$' => $user->getLastName(),
                    '${firstName}$' => $user->getFirstName(),
                    '${middleName}$' => $user->getMiddleName(),
                    '${part}$' => (int) $part,
                    '${id}$' => $handlingId,
                    '${url}$' => '<a href="' . $url . '">' . $url . '</a>',
                )
            )
        );
    }
    /**
     * sendEmailRemoveManagerProject
     * 
     * @param integer $handlingId
     * @param string  $email
     * @param object  $user
     */
    private function sendEmailRemoveManagerProject($handlingId, $email, $user)
    {
        $emailService = $this->container->get('it_doors_email.service');
        $emailService->send(
            null,
            'remove-manager-project',
            array(
                'users' => array(
                    $email
                ),
                'variables' => array(
                    '${lastName}$' => $user->getLastName(),
                    '${firstName}$' => $user->getFirstName(),
                    '${middleName}$' => $user->getMiddleName(),
                    '${id}$' => $handlingId,
                )
            )
        );
    }
}
