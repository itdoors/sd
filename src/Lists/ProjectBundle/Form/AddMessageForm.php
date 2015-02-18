<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Lists\ProjectBundle\Form\MessageCurrentForm;
use Lists\ProjectBundle\Form\MessagePlannedForm;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AddMessageForm
 */
class AddMessageForm extends AbstractType
{
    protected $em;
    protected $router;
    protected $translator;
    /**
     * __construct
     *
     * @param EntityManager $em
     * @param Router        $router
     * @param Translator    $translator
     */
    public function __construct(EntityManager $em, Router $router, Translator $translator)
    {
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('current', new MessageCurrentForm($this->em, $this->router, $this->translator))
            ->add('planned', new MessagePlannedForm($this->em, $this->router, $this->translator));
        $builder
            ->add('submit', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsProjectBundle',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'addMessageForm';
    }
}
