<?php

namespace Lists\HandlingBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Classes\HandlingAccessFactory;
use SD\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * HandlingService class
 */
class HandlingService
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
     * checkAccess
     * 
     * @param User     $user
     * @param Handling $handling
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user, Handling $handling = null)
    {
        $role = array();
        if ($handling) {
            $handlingUsers = $handling->getHandlingUsers();
            foreach ($handlingUsers as $handlingUser) {
                $rol = $handlingUser->getLookup();
                if ($rol && $rol->getLukey() == 'manager_project' && $handlingUser->getUser() == $user) {
                    $role[] = 'ManagerProject';
                }
                if ($rol && $rol->getLukey() == 'manager' && $handlingUser->getUser() == $user) {
                    $role[] = 'Manager';
                }
            }
        }
        if ($user->hasRole('ROLE_SALESADMIN')) {
            $role[] = 'SalesAdmin';
        }
        if ($user->hasRole('ROLE_GOS_TENDER_ADMIN')) {
            $role[] = 'GosTenderAdmin';
        }
        if ($user->hasRole('ROLE_GOS_TENDER')) {
            $role[] = 'GosTender';
        }
        if ($user->hasRole('ROLE_GOS_DIRECTOR')) {
            $role[] = 'GosTenderDirector';
        }
        if ($user->hasRole('ROLE_REPORT')) {
            $role[] = 'Report';
        }
        if ($user->hasRole('ROLE_SALES')) {
            $role[] = 'Sales';
        }

        return HandlingAccessFactory::createAccess($role, $handling);
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveGosTenderParticipationForm (Form $form, Request $request, $params)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $data = $form->getData();
        $isParticipation = $data['isParticipation'];
        $reason = $data['reason'];
        $gosTender = $em->getRepository('ListsHandlingBundle:ProjectGosTender')->find((int)$data['gosTenderId']);
        if ($gosTender) {
            $gosTender->setIsParticipation($isParticipation);
            if (empty($isParticipation)) {
                $gosTender->setReason($reason);
                $gosTender->getProject()->setIsClosed(true);
                $gosTender->getProject()->setDatetimeClosed(new \DateTime());
                $gosTender->getProject()
                    ->setClosedUser($this->container->get('security.context')->getToken()->getUser());
            }
            $em->persist($gosTender);
            $em->flush();
        }
    }
     /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function projectGosTenderParticipanFormDefaults(Form $form, $defaults)
    {
        /** @var EntityManager $em */
//        $em = $this->container->get('doctrine.orm.entity_manager');
////        $organizationId = (int) $defaults['participan'];
////        $organization = $em->getRepository('ListsOrganizationBundle:Organization')->find($organizationId);
//        $gosTenderId = (int) $defaults['gosTender'];
//        $gosTender = $em->getRepository('ListsHandlingBundle:ProjectGosTender')->find($gosTenderId);
//        $form
////            ->add('participan', 'entity', array(
////                'class'=>'Lists\OrganizationBundle\Entity\Organization',
////                'data' => $organization
////            ))
//            ->add('gosTender', 'hidden_entity', array(
//                'entity'=>'Lists\HandlingBundle\Entity\ProjectGosTender',
//                'data' => $gosTender
//            ));
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveProjectGosTenderParticipanForm (Form $form, Request $request, $params)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $data = $form->getData();
        $em->persist($data);
        $em->flush();
    }
}
