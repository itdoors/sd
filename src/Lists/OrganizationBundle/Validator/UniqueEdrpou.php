<?php

namespace Lists\OrganizationBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class UniqueEdrpou extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'unique_edrpou';
    }
}
