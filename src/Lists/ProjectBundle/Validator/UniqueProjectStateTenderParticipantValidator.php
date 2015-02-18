<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\Form;

/**
 * Class UniqueProjectStateTenderParticipantValidator
 */
class UniqueProjectStateTenderParticipantValidator extends ConstraintValidator
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
            $projectStateTender = $root->get('projectStateTender')->getData();
        } elseif ($value && $root->get('participan')) {
            $projectStateTender = $root->getProjectStateTender();
        }
        $conflict = $this->em
            ->getRepository('ListsProjectBundle:ProjectStateTenderParticipant')
            ->findOneBy(array('participant' => $value, 'projectStateTender' => $projectStateTender));
        if ($conflict) {
            $this->context->addViolation('Participant already', array());
        }
    }
}
