<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class UniqueStateTender extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'unique_state_tender';
    }
}
