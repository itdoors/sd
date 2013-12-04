<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;



class ModelContactOrganizationAdminFormType extends ModelContactOrganizationFormType
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;

        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $this->ur = $this->container->get('sd_user.repository');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
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
