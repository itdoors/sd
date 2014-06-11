<?php

namespace Lists\HandlingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class CreatedateRange extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'createdate_range';
    }
}
