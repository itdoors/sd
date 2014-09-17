<?php

namespace SD\CalendarBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class HolidayForm
 */
class HolidayForm extends AbstractType
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
            ->add('date', 'text', array(
                'mapped' => false
            ))
            ->add('name', 'text')
            ->add('shortDescription', 'textarea')
            ->add('description', 'textarea');
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
            'data_class' => 'SD\CalendarBundle\Entity\Holiday',
            'translation_domain' => 'SDCalendarBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'holidayForm';
    }
}
