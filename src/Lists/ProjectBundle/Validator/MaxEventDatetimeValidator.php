<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\Form;

/**
 * Class MaxEventDatetimeValidator
 */
class MaxEventDatetimeValidator extends ConstraintValidator
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
        if (!$root instanceof \Lists\ProjectBundle\Entity\Message) {
            throw new \Exception('Error instanceof');
        }
        $createDate = $root->getEventDatetimeStart();
        $dateMin = $createDate;
        $dateMax = clone $createDate;
        $dateMax->modify('+15 days');
        $dateEvent = $root->getEventDatetime();
        if ($dateMin > $dateEvent || $dateMax < $dateEvent) {
            $this->context->addViolation(
                'Range resolution date from :minDate to :dateMax',
                array(':minDate' => $dateMin->format('d-m-Y H:i:s'), ':dateMax' => $dateMax->format('d-m-Y H:i:s'))
            );
        }
    }
}
