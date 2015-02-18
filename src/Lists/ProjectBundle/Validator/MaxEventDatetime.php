<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class MaxEventDatetime extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'maxEventDatetime';
    }
}
