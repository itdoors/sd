<?php

namespace Lists\ContactBundle\Form;

use Lists\ContactBundle\Entity\ModelContactLevelRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;

/**
 * Class ModelContactOrganizationFormType
 */
class ModelContactOrganizationFormType extends AbstractType
{
    /**
     * {@inheritdoc}
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
            ->add('level', null, array(
                'required' => false,
                'query_builder' => function (ModelContactLevelRepository $repository) {
                        return $repository->createQueryBuilder('mcl')
                            ->orderBy('mcl.digit', 'ASC');
                }
            ));

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
        return 'modelContactOrganizationForm';
    }
}
