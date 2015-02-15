<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Lists\ProjectBundle\Entity\ProjectStateTender;

/**
 * Class UniqueProjectStateTenderValidator
 */
class UniqueProjectStateTenderValidator extends ConstraintValidator
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
        $datetimeDeadline = null;
        if ($root instanceof ProjectStateTender) {
            $datetimeDeadline = $root->getDatetimeDeadline();
        } elseif ($value && $root->get('datetimeDeadline')) {
            $datetimeDeadline = $root->get('datetimeDeadline')->getData();
        }
        if ($datetimeDeadline) {
            $conflict = $this->em
                    ->getRepository('ListsProjectBundle:ProjectStateTender')
                    ->findOneBy(array('advert' => $value, 'datetimeDeadline' => $datetimeDeadline));
            if ($conflict) {
                $this->context->addViolation('Project already suchestvuet', array());
            }
        }
    }
}
