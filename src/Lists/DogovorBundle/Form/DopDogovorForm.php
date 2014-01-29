<?php

namespace Lists\DogovorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DopDogovorForm extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dopDogovorType')
            ->add('number')
            ->add('startdatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('activedatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('subject')
            ->add('isActive')
            ->add('file', 'file', array(
                'required' => false
            ))
            ->add('total')
            ->add('dogovorId', 'hidden')
            ->add('saller', 'hidden_entity', array(
                'entity' => 'SDUserBundle:User'
            ))
        ;

        $builder
            ->add('add', 'submit')
            ->add('cancel', 'button');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\DogovorBundle\Entity\DopDogovor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dopDogovorForm';
    }
}
