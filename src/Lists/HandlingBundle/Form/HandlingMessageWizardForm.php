<?php

namespace Lists\HandlingBundle\Form;

use Lists\HandlingBundle\Entity\HandlingMessage;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class HandlingMessageWizardForm
 */
class HandlingMessageWizardForm extends AbstractType
{
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
        $container = $this->container;

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $container->get('lists_lookup.repository');

        $builder
            ->add('createdate', 'datetime', array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'format' => 'dd.M.yyyy HH:mm'
            ))
            ->add('type', null, array(
                'empty_value' => '',
                'required' => true,
            ))
            ->add('nextcreatedate', 'datetime', array(
                'required' => true,
                'mapped' => false,
                'widget' => 'single_text',
                'format' => 'dd.M.yyyy HH:mm',
                'empty_value' => ''
            ))
            ->add('nexttype', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingMessageType',
                'required' => true,
                'empty_value' => '',
                'mapped' => false
            ))
            ->add('next_is_business_trip', 'checkbox', array(
                'mapped' => false,
                'required' => false
            ))
            ->add('description')
            ->add('descriptionnext', 'text', array(
                'required' => false,
                'mapped' => false
            ))
            ->add('filename')
            ->add('file', 'file', array(
                'required' => false
            ))
            ->add('handling_id', 'hidden')
            ->add('mindate', 'hidden', array(
                'mapped' => false
            ));

        $builder
            ->add('create', 'submit');

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                $currentDatetime = new \DateTime($data['createdate']);
                $nextDatetime = new \DateTime($data['nextcreatedate']);

                if ($currentDatetime > $nextDatetime) {
                    $translator = $container->get('translator');

                    $msgString = "Event next date can't be greater then current event date";

                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');

                    $form->addError(new FormError($msg));
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                if (!$data['nextcreatedate']) {
                    $translator = $container->get('translator');

                    $msg = $translator->trans("Event next date can't be empty", array(), 'ListsHandlingBundle');

                    $form->addError(new FormError($msg));
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                $currentDatetime = new \DateTime($data['createdate']);
                $session = $this->container->get('session');
                $handling = $session->get('sales.wizard.handling');

                $handlingCreatedate = $handling->getCreatedate();

                if ($handlingCreatedate > $currentDatetime) {
                    $translator = $container->get('translator');

                    $creationDate = $handlingCreatedate;

                    $msgString = "Current event date can't be less then handling creation date (%date%)";

                    $msg = $translator->trans($msgString, array(
                        '%date%' => $creationDate->format('d.m.y')
                    ), 'ListsHandlingBundle');

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
            'data_class' => 'Lists\HandlingBundle\Entity\HandlingMessage',
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingMessageWizardForm';
    }
}
