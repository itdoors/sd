<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;

/**
 * ModelContactOrganizationUserFormType
 */
class ModelContactOrganizationUserFormType extends ModelContactOrganizationFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
                'format' => 'dd.MM.yyyy',
                'years' => range(1900, date('Y'))
            ))
            ->add('email');

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
            'data_class' => 'Lists\ContactBundle\Entity\ModelContact',
            'translation_domain' => 'ListsContactBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelContactOrganizationUserForm';
    }
}
