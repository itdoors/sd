<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class OrganizationChildForm extends AbstractType
{
    /**
     * @var Container $container
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Container $container */
        $container = $this->container;

        $builder
            ->add('organizationChildId', 'hidden')
            ->add('organizationId', 'hidden');

        $builder
            ->add('add', 'submit')
            ->add('cancel', 'button');

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($container)
            {
                $data = $event->getData();

                $form = $event->getForm();

                /** @var Translator $translator*/
                $translator = $container->get('translator');

                $msg = '';
                $error = false;

                if (!$data['organizationChildId'])
                {
                    $msg = $translator->trans("Child organization cant be empty", array(), 'ListsOrganizationBundle');
                    $error = true;
                }

                if ($data['organizationChildId'] && $data['organizationChildId'] == $data['organizationId'])
                {
                    $msg = $translator->trans("You've picked the same organization", array(), 'ListsOrganizationBundle');
                    $error = true;
                }

                if ($error)
                {
                    $form->get('organizationChildId')->addError(new FormError($msg));
                }
            });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'ListsOrganizationBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationChildForm';
    }
}
