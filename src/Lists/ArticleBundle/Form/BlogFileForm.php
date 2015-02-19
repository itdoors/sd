<?php

namespace Lists\ArticleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class BlogFileForm
 */
class BlogFileForm extends AbstractType
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
            'data_class' => 'ITDoors\FileAccessBundle\Entity\BlogFile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'blogFileForm';
    }
}
