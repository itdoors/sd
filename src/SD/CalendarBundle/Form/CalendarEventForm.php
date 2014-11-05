<?php

namespace SD\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CalendarEventForm class
 */
class CalendarEventForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventName')
            ->add('description')
            ->add('fromdatetime')
            ->add('todatetime')
            ->add('isNotifi')
            ->add('notificationTime')
            ->add('isFullDay')
            ->add('additionalData')
            ->add('type')
            ->add('user');

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
            'data_class' => 'SD\CalendarBundle\Entity\CalendarEvent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'calendarEventForm';
    }
}
