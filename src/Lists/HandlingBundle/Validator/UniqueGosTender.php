<?php

namespace Lists\HandlingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class UniqueGosTender extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'unique_gos_tender';
    }
}
