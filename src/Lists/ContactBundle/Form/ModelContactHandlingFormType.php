<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;

/**
 * Class ModelContactHandlingFormType
 */
class ModelContactHandlingFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('modelName', 'hidden', array(
                'data' => ModelContactRepository::MODEL_HANDLING
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
                'format' => 'dd.MM.yyyy',
                'years' => range(1900, date('Y')),
                'required' => false,
            ))
            ->add('email');

        $builder
            ->add('add', 'submit');
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
        return 'modelContactHandlingForm';
    }
}
