<?php

namespace Lists\OrganizationBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

class UniqueEdrpouValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value)
        {
            $root = $this->context->getRoot();

            $conflicts = $this->em
                ->getRepository('ListsOrganizationBundle:Organization')
                ->findByEdrpou($value);

            if (count($conflicts) > 0 && isset($conflicts[0]))
            {
                $organization = $conflicts[0];

                if ($root)
                {
                    if ($root->getId() != $organization->getId())
                    {
                        $this->context->addViolation('Organization with edrpou = %edrpou% already exists. %OrganizationName% %OrganizationShortName%',
                            array(
                                '%edrpou%' => $value,
                                '%OrganizationName%' => $organization->getName(),
                                '%OrganizationShortName%' => $organization->getShortname(),
                            ));
                    }
                }
            }
        }
    }
}