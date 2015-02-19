<?php

namespace ITDoors\FileAccessBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ClaimFileForm
 */
class ClaimFileForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'required' => false,
                'multiple' => false,
                'attr' => array(
                    'style' => 'display: inline;'
                    )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ITDoors\FileAccessBundle\Entity\ClaimFile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'claimFileForm';
    }
}
