<?php

namespace Lists\ProjectBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Form\Form;

/**
 * Class ManagerUniqueUserValidator
 */
class ManagerUniqueUserValidator extends ConstraintValidator
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
            $user = $root->get('user')->getData();
            $project = $root->get('project')->getData();
        } else {
            $user = $root->getUser();
            $project = $root->getProject();
        }
        $conflict = $this->em->getRepository('ListsProjectBundle:Manager')->findOneBy(array(
            'user' => $user,
            'project' => $project
        ));
        if ($conflict) {
            $this->context->addViolation('Manager already', array());
        } 
    }
}
