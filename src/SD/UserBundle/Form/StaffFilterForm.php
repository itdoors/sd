<?php

namespace SD\UserBundle\Form;

use SD\UserBundle\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StaffFilterForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
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
        /** @var DogovorRepository $u */
        $u = $this->container->get('sd_user.repository');

        $translator = $this->container->get('translator');

        $builder
            ->add('firstName')
            ->add('email')
            ->add('position')
            ->add('isActive','choice', array(
                'choices'   =>  array(
                       'active' => $translator->trans("Active", array(), 'SDUserBundle.ru'),
                        'blocked' => $translator->trans("Blocked", array(), 'SDUserBundle.ru'),
                    ),
                'empty_value' =>  ''
            ))
            ->add('isFired','choice', array(
                'choices'   =>  array(
                        'fired' => $translator->trans("Fired", array(), 'SDUserBundle.ru'),
                        'No fired' => $translator->trans("No fired", array(), 'SDUserBundle.ru'),
                    ),
                'empty_value' =>  ''
            ))
            ;


        $builder
            ->add('save', 'submit')
            ->add('reset', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'SDUserBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'staffFilterForm';
    }
}
