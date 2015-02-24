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
            $partMax = 0;
            if ($project->getManagerProject()) {
                $partMax = $project->getManagerProject()->getPart();
            }
            $group = $this->context->getGroup();
            if ($group == 'edit') {
                $this->em->refresh($root);
                $man = $this->em->getRepository('ListsProjectBundle:Manager')->find($root->getId());
                $partMax += ($man->getPart()*2);
                $root->setPart($value);
            }
            if ($partMax < $value) {
                $this->context->addViolation('Max part :maxPart', array(':maxPart' => $partMax));
            }
        } else {
            $this->context->addViolation('Project not found', array());
        }
    }
}
