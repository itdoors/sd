<?php

namespace Lists\DepartmentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BankForm
 */
class BankForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('mfo', 'text', array(
                'required' => true
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
            'data_class' => 'Lists\OrganizationBundle\Entity\Bank',
            'translation_domain' => 'ListsOrganizationBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bankForm';
    }
}
