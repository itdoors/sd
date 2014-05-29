<?php

namespace Lists\TeamBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TeamFormType
 */
class TeamFormType extends AbstractType
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
        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $ur = $this->container->get('sd_user.repository');

        $builder
            ->add('name')
            ->add('description')
            ->add('users', 'entity', array(
                'class'=>'SD\UserBundle\Entity\User',
                'multiple' => true,
                'property'=>'fullname',
                'query_builder' => $ur->getOnlyStaff()
            ));

        $builder
            ->add('save', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\TeamBundle\Entity\Team',
            'validation_groups' => array('new')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'teamForm';
    }
}
