<?php

namespace ITDoors\EmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * clasds FileType
 */
class FileType extends AbstractType
{

    /**
     * buildForm
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     *
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'file');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ITDoors\EmailBundle\Entity\File',
            'translation_domain' => 'ITDoorsEmailBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'itdoors_emailbundle_Filetype';
    }
}
