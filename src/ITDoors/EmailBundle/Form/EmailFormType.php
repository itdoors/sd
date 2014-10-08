<?php

namespace ITDoors\EmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EmailFormType
 * 
 * @package ITDoors\EmailBundle\Form
 */
class EmailFormType extends AbstractType
{

    protected $container;

    /**
     *  __construct
     * 
     * @param obj $container Description
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param object $builder desc
     * @param array  $options desc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias', 'text')
            ->add('subject', 'text')
            ->add('text', 'textarea', array(
                'required' => true,
                'mapped' => true
            ));
        $builder
            ->add('create', 'submit')
            ->add('cancel', 'button');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ITDoors\EmailBundle\Entity\Email',
            'validation_groups' => array('new'),
            'translation_domain' => 'ITDoorsEmailBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'emailForm';
    }
}
