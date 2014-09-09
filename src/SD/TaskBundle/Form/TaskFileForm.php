<?php

namespace SD\TaskBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TaskFileForm
 */
class TaskFileForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('name')
            ->add('file', 'file', array(
                'required' => true
            ));
/*            ->add('idTask', 'hidden',  array(
                'mapped' => false
                //'data' => $defaults['idTask']

            ));*/

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\TaskBundle\Entity\TaskFile',
            /*'validation_groups' => array('new'),*/
            'translation_domain' => 'SDTaskBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'taskFileForm';
    }
}
