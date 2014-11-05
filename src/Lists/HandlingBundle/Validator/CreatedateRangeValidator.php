<?php

namespace Lists\HandlingBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

/**
 * Class CreatedateRangeValidator
 */
class CreatedateRangeValidator extends ConstraintValidator
{
    private $em;

    /**
     * __construct
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * validate
     *
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value) {
            if ($value > new \DateTime()) {
                $this->context->addViolation('Creation date can\'t be greater then current date ', array());
            }
        }
    }
}
