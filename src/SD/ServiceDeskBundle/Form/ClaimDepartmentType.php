<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClaimDepartmentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array('choices'   => \SD\ServiceDeskBundle\Entity\ClaimType::values()))
            ->add('importance')
            ->add('text')
            ->add('targetDepartment')
            ->add('customer')
            ->add('files', 'collection', array(
                'required' => false,
                'type'=> new \SD\ServiceDeskBundle\Form\ClaimFileForm(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty'=> true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\ClaimDepartment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sd_servicedeskbundle_claimdepartment';
    }
}
