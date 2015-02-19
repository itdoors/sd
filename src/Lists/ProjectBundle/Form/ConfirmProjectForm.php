<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ConfirmProjectForm
 */
class ConfirmProjectForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statusAccess', 'itdoors_choice', array(
                'required' => true,
                'empty_value' => '',
                'attr' => array(
                    'class' => 'form-control can-be-reseted',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0
                    )),
                ),
                'choices' => array(
                    1 => 'Yes',
                    0 => 'No'
                    )
                ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ProjectBundle\Entity\Project',
            'validation_groups' => array('confirm'),
            'translation_domain' => 'ListsProjectBundle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'confirmProjectForm';
    }
}
