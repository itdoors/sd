<?php

namespace SD\ServiceDeskBundle\Classes;

/**
 * ClaimAccessFactory class
 */
class ClaimAccessFactory
{

    public static function createAccess($user, $claim)
    {
        if ($user->hasRole('ROLE_SUPERVISOR')) {
            return new SupervisorAccess();
        } elseif ($user->hasRole('ROLE_DISPATCHER')) {
            return new DispatcherAccess();
        } elseif ($user->hasRole('ROLE_CLAIM_CLIENT')) {
            return new ClientAccess();
        } else {
            foreach ($claim->getClaimPerformerRules() as $rule) {
                //echo $rule->getClaimPerformer()->getIndividual()->getUser()->getUsername();
                if ($rule->getClaimPerformer()->getIndividual()->getUser() == $user) {
                    $canPostToClient = $rule->getCanPostToClients();
                    $canEditFinanceData = $rule->getCanEditFinanceData();
                    return new PerformerAccess($canEditFinanceData, $canPostToClient);
                }
            }
            return new BasicAccess();
        }
    }
}
