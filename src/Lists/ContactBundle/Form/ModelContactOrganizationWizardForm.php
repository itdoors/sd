<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

/**
 * Class ModelContactOrganizationWizardForm
 */
class ModelContactOrganizationWizardForm extends ModelContactOrganizationFormType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $container = $this->container;

        $session = $container->get('session');
        $organization = $session->get('sales.wizard.organization');
        $organizationstr = isset($organization['organizationName']) ? $organization['organizationName'] : null;

        $builder
            ->add('organization', 'text', array(
                'disabled' => true,
                'data' => (string) $organizationstr
        ));

        $builder
            ->remove('modelId')
            ->remove('cancel');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelContactOrganizationWizardForm';
    }
}
