<?php

namespace Lists\KvedBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class KvedFormType
 */
class KvedFormType extends AbstractType
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', 'entity', array(
                'class' => 'Lists\KvedBundle\Entity\Kved',
                'empty_value' => ''
                ))
            ->add('code', 'text')
            ->add('name', 'text')
            ->add('description')
            ->add('save', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\KvedBundle\Entity\Kved',
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'ListsKvedBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kvedForm';
    }
}
