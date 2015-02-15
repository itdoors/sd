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
use Doctrine\ORM\EntityRepository;

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
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
      * 
      * @return Form $form
     */
    public function addPayMasterBankFormDefaults(Form $form, $defaults)
    {
        $organization = $this->em->getRepository('ListsOrganizationBundle:Organization')
            ->find((int) $defaults['organizationId']);

        $form
            ->add('organizationId', 'hidden', array(
                'mapped' => false
            ))
            ->add('bank', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\Bank',
                'empty_value' => '',
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($organization) {

                    return $er->createQueryBuilder('b')
                        ->leftJoin('b.currentAccounts', 'c')
                        ->leftJoin('c.organization', 'o')
                        ->where('o.id = :organization')
                        ->setParameter(':organization', $organization)
                        ->orderBy('b.name', 'ASC');
                }
            ));
    }
    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function savePayMasterBankForm (Form $form, Request $request, $params)
    {
        $access = $this->checkAccess($this->getUser());
        $data = $form->getData();
        $bank = $this->em->getRepository('ListsOrganizationBundle:Bank')->find($data['bank']);
        $payMaster = $this->em->getRepository('ITDoorsPayMasterBundle:PayMaster')->find($data['payMasterId']);
        if (!$access->canEditBank() || $payMaster->getBank() != '') {
            throw new Exception('No access', 403);
        }
        if ($bank && $payMaster) {
            $payMaster->setBank($bank);
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
                ($access->canSeeAll() ? 'Received' : 'Unpaid'),
                array (),
                'ITDoorsPayMasterBundle'
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
