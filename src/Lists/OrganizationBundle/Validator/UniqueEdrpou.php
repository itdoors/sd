<?php

namespace Lists\OrganizationBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class UniqueEdrpou extends Constraint
{
    public function validatedBy()
    {
        return 'unique_edrpou';
    }
/*
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }*/
}