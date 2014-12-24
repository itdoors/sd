<?php

namespace ITDoors\PayMasterBundle\Services;

use ITDoors\PayMasterBundle\Classes\PayMasterAccessFactory;
use SD\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Routing\Router;

/**
 * PayMasterService class
 */
class PayMasterService
{

    /** @var EntityManager $em */
    protected $em;
    protected $context;
    protected $translator;
    protected $router;

    /**
     * __construct
     *
     * @param EntityManager   $em
     * @param SecurityContext $context
     * @param Translator      $translator
     * @param Router          $router
     */
    public function __construct (EntityManager $em, SecurityContext $context, Translator $translator, Router $router)
    {
        /** @var EntityManager $em */
        $this->em = $em;
        $this->context = $context;
        $this->translator = $translator;
        $this->router = $router;
    }
    /**
     * getUser
     * 
     * @return User
     */
    public function getUser ()
    {
        return $this->context->getToken()->getUser();
    }
    /**
     * checkAccess
     * 
     * @param User $user
     * 
     * @return mixed[]
     */
    public function checkAccess (User $user)
    {
        $role = array ();
        if ($user->hasRole('ROLE_PAY_MASTER')) {
            $role[] = 'payMaster';
        }
        if ($user->hasRole('ROLE_PAY_MASTER_CONTROLLING')) {
            $role[] = 'payMasterControlling';
        }

        return PayMasterAccessFactory::createAccess($role);
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function savePayMasterAcceptanceForm (Form $form, Request $request, $params)
    {
        $data = $form->getData();
        $isAcceptance = $data['isAcceptance'];
        $reason = $data['reason'];
        $payMaster = $this->em->getRepository('ITDoorsPayMasterBundle:PayMaster')->find($data['payMasterId']);
        if ($payMaster) {
            $payMaster->setIsAcceptance($isAcceptance);
            if (empty($isAcceptance)) {
                $payMaster->setReason($reason);
            }
            $this->em->persist($payMaster);
            $this->em->flush();
        }
    }
    /**
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabs ()
    {
        $access = $this->checkAccess($this->getUser());
        $tabs = array ();
        $tabs['new'] = array (
            'blockupdate' => 'tab-content-block',
            'tab' => 'new',
            'url' => $this->router->generate('it_doors_pay_master_tab'),
            'text' => $this->translator->trans(
                ($access->canSeeAll() ? 'Received' : 'Unpaid'), array (), 'ITDoorsPayMasterBundle'
            )
        );
        if ($access->canSeeAll()) {
            $tabs['urgent'] = array (
                'blockupdate' => 'tab-content-block',
                'tab' => 'urgent',
                'url' => $this->router->generate('it_doors_pay_master_tab'),
                'text' => $this->translator->trans('Urgent', array (), 'ITDoorsPayMasterBundle')
            );
            $tabs['payment'] = array (
                'blockupdate' => 'tab-content-block',
                'tab' => 'payment',
                'url' => $this->router->generate('it_doors_pay_master_tab'),
                'text' => $this->translator->trans('On payment', array (), 'ITDoorsPayMasterBundle')
            );
        }
        $tabs['sponsored'] = array (
            'blockupdate' => 'tab-content-block',
            'tab' => 'sponsored',
            'url' => $this->router->generate('it_doors_pay_master_tab'),
            'text' => $this->translator->trans('Sponsored', array (), 'ITDoorsPayMasterBundle')
        );
        $tabs['rejected'] = array (
            'blockupdate' => 'tab-content-block',
            'tab' => 'rejected',
            'url' => $this->router->generate('it_doors_pay_master_tab'),
            'text' => $this->translator->trans('Rejected', array (), 'ITDoorsPayMasterBundle')
        );

        return $tabs;
    }
}
