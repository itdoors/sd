<?php

namespace ITDoors\PayMasterBundle\Services;

use ITDoors\PayMasterBundle\Classes\PayMasterAccessFactory;
use SD\UserBundle\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

/**
 * PayMasterService class
 */
class PayMasterService
{
    /** @var EntityManager $em */
    protected $em;
    /**
     * __construct
     *
     * @param EntityManager $em
     */
    public function __construct (EntityManager $em)
    {
        /** @var EntityManager $em */
        $this->em = $em;
    }
    /**
     * checkAccess
     * 
     * @param User $user
     * 
     * @return mixed[]
     */
    public function checkAccess(User $user)
    {
        $role = array();
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
    public function savePayMasterAcceptanceForm(Form $form, Request $request, $params)
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
}
