<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;

class ModelContactOrganizationFormType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modelName', 'hidden', array(
                'data' => ModelContactRepository::MODEL_ORGANIZATION
            ))
            ->add('modelId', 'text')
            ->add('firstName')
            ->add('lastName')
            ->add('middleName')
            ->add('phone1')
            ->add('phone2')
            ->add('position')
            ->add('birthday', 'birthday', array(
                'widget' => 'single_text',
                'format' => 'dd.M.yyyy'
            ))
            ->add('email')
            ->add('type', null, array(
                'required' => true
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
            'data_class' => 'Lists\ContactBundle\Entity\ModelContact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelContactOrganizationForm';
    }
}
