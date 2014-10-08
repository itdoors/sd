<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ModelContactOrganizationAdminFormType
 */
class ModelContactOrganizationAdminFormType extends ModelContactOrganizationFormType
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('owner', 'hidden_entity', array(
                'entity' => 'SDUserBundle:User'
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelContactOrganizationAdminForm';
    }
}
