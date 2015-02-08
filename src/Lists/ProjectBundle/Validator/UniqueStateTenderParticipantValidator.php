<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\Form;

/**
 * Class UniqueStateTenderParticipantValidator
 */
class UniqueStateTenderParticipantValidator extends ConstraintValidator
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
            $stateTender = $root->get('stateTender')->getData();
        } elseif ($value && $root->get('participan')) {
            $stateTender = $root->getStateTender();
        }
        $conflict = $this->em
            ->getRepository('ListsProjectBundle:StateTenderParticipant')
            ->findOneBy(array('participant' => $value, 'stateTender' => $stateTender));
        if ($conflict) {
            $this->context->addViolation('Participant already', array());
        }
    }
}
