<?php

namespace Lists\IndividualBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'choices'   => \Lists\IndividualBundle\Entity\ContactType::values(),
                'attr' => array(
                    'class' => 'form-control input-inline',
//                     'data-params' => json_encode(array(
//                         'width' => '30%',
//                         'minimumInputLength' => 0,
//                     ))
                )
            ))
            ->add('value', null, array(
                'attr' => array(
                    'class' => 'form-control input-inline'
                )
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\IndividualBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lists_individualbundle_contact';
    }
}
