<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ModelContactOrganizationWizardForm
 */
class ModelContactOrganizationWizardForm extends ModelContactOrganizationFormType
{
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $this->ur = $this->container->get('sd_user.repository');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

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
