<?php

namespace ITDoors\EmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Symfony\Component\Form\FormEvents;

/**
 * Class EmailFormType
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
//        $container = $this->container;
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

//        $builder->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function (FormEvent $event) use ($container) {
//                $data = $event->getData();
//                $form = $event->getForm();
//            }
//        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ITDoors\EmailBundle\Entity\Email',
            'validation_groups' => array('new'),
            'translation_domain' => 'EmailBundle'
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
