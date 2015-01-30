<?php

namespace Lists\HandlingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class UniqueProjectGosTenderParticipan extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'unique_project_gos_tender_participan';
    }
}
