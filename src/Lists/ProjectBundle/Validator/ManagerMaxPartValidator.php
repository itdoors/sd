<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\Form;

/**
 * Class ManagerMaxPartValidator
 */
class ManagerMaxPartValidator extends ConstraintValidator
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
        $root = $this->context->getRoot();
        if ($root instanceof Form) {
            $project = $root->get('project')->getData();
        } else {
            $project = $root->getProject();
        }
        if (isset($project)) {
            $managers = $project->getManagers();
            $part = 0;
            foreach ($managers as $manager) {
                if ($manager->isManagerProject()) {
                    $part = $manager->getPart();
                }
            }
            if ($part < $value) {
                $this->context->addViolation('Max part :maxPart', array(':maxPart' => $part));
            }
        } else {
            $this->context->addViolation('Project not found', array());
        }
    }
}
