<?php

namespace Lists\OrganizationBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Translation\Translator;

/**
 * Class UniqueEdrpouValidator
 */
class UniqueEdrpouValidator extends ConstraintValidator
{
    private $em;
    private $translator;

    /**
     * @param EntityManager $em
     * @param Translator    $translator
     */
    public function __construct(EntityManager $em, Translator $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value) {
            $root = $this->context->getRoot();
            $conflicts = $this->em
                ->getRepository('ListsOrganizationBundle:Organization')
                ->findByEdrpou($value);

            if (count($conflicts) > 0 && isset($conflicts[0])) {
                $organization = $conflicts[0];

                if ($root && $organization) {
                    if (!method_exists($root, 'getId')
                        ||
                        (method_exists($root, 'getId') && $root->getId() != $organization->getId())) {
                        $msgString =
                            $this->translator->trans('Organization with edrpou', array(), 'ListsOrganizationBundle').
                            ' = %edrpou% '.
                            $this->translator->trans('already exists', array(), 'ListsOrganizationBundle').' '.
                            '%OrganizationName% %OrganizationShortName%';

                        $this->context->addViolation(
                            $msgString,
                            array(
                                '%edrpou%' => $value,
                                '%OrganizationName%' => $organization->getName(),
                                '%OrganizationShortName%' => $organization->getShortname(),
                            )
                        );
                    }
                }
            }
        }
    }
}
