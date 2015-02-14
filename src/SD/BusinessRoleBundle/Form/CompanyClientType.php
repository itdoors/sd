<?php

namespace SD\BusinessRoleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grantedOrganizations')
            ->add('departments')
            ->add('originOrganizations')
            ->add('individual');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\BusinessRoleBundle\Entity\CompanyClient'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sd_businessrolebundle_companyclient';
    }
}
