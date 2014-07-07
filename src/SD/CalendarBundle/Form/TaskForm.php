<?php

namespace SD\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\DependencyInjection\Container;

/**
 * TaskForm class
 */
class TaskForm extends AbstractType
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;
        
        $builder
            ->add('title')
            ->add('description')
            ->add('startDateTime', 'text')
            ->add('stopDateTime', 'text');
        
         $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                /** @var Task $data */
                $data = $event->getData();

                $form = $event->getForm();

                $translator = $container->get('translator');
                $dataStart = new \DateTime($data->getStartDateTime());
                $dataStop = new \DateTime($data->getStopDateTime());
                if ($dataStart->format('U') >= $dataStop->format('U')) {
                    $msgString = "Start date can't be greater then stop date";

                    $msg = $translator->trans($msgString, array(), 'SDCalendarBundle');

                    $form->addError(new FormError($msg));
                }
            }
        );

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\CalendarBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'taskForm';
    }
}
