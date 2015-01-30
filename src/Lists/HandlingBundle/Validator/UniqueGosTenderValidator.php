<?php

namespace Lists\HandlingBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueGosTenderValidator
 */
class UniqueGosTenderValidator extends ConstraintValidator
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
        if (method_exists($root, 'getDatetimeDeadline')) {
            $conflict = $this->em
                ->getRepository('ListsHandlingBundle:ProjectGosTender')
                ->findOneBy(array('advert' => $value, 'datetimeDeadline' => $root->getDatetimeDeadline()));
        } elseif ($value && $root->get('datetimeDeadline')) {
            $conflict = $this->em
                ->getRepository('ListsHandlingBundle:ProjectGosTender')
                ->findOneBy(array('advert' => $value, 'datetimeDeadline' => $root->get('datetimeDeadline')->getData())); 
        }
        if ($conflict) {
            $this->context->addViolation('Project already suchestvuet', array());
        }
    }
}
