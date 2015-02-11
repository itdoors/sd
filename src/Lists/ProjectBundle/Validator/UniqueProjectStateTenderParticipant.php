<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class UniqueProjectStateTenderParticipant extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'unique_state_tender_participant';
    }
}
