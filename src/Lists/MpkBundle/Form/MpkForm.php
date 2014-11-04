<?php

namespace Lists\MpkBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MpkForm
 */
class MpkForm extends AbstractType
{
    /**
     * @var Container $container
     */
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
        $translator = $this->container->get('translator');

        $builder
            ->add('name', 'text')
            ->add('startDate', 'text', array(
                'required' => false
            ))
            ->add('endDate', 'text', array(
                'required' => false
            ))
            ->add('active', 'choice', array(
                'attr' => array(
                    'class' => 'form-control input-middle',
                ),
                'choices' => array(
                    '' => $translator->trans('Select status', array(), 'ListsMpkBundle'),
                    '1' => $translator->trans('Active', array(), 'ListsMpkBundle'),
                    '0' => $translator->trans('Don`t active', array(), 'ListsMpkBundle')
                )
            ))
            ->add('organization', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\Organization',
                'property'=>'name',
                'empty_value' => ''
            ))
            ->add('department', 'entity', array(
                'class'=>'Lists\DepartmentBundle\Entity\Departments',
                'property'=>'name',
                'empty_value' => ''
            ))
            ->add('create', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\MpkBundle\Entity\Mpk',
            'translation_domain' => 'ListsMpkBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mpkForm';
    }
}
