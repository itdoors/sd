<?php

namespace Lists\HandlingBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueProjectGosTenderParticipanValidator
 */
class UniqueProjectGosTenderParticipanValidator extends ConstraintValidator
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
        $conflict = null;
        if (method_exists($root, 'getGosTender')) {
            $conflict = $this->em
                ->getRepository('ListsHandlingBundle:ProjectGosTenderParticipan')
                ->findOneBy(array('participan' => $value, 'gosTender' => $root->getGosTender()));
        } elseif ($value && $root->get('participan')) {
            $conflict = $this->em
                ->getRepository('ListsHandlingBundle:ProjectGosTenderParticipan')
                ->findOneBy(array('participan' => $value, 'gosTender' => $root->get('gosTender')->getData())); 
        }
        if ($conflict) {
            $this->context->addViolation('Participan already', array());
        }
    }
}
