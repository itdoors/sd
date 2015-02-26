<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SD\UserBundle\SDUserBundle;

/**
 * ClaimMessageType
 */
class ClaimMessageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('staffOnly', null, array(
                'required' => false
            ))
            ->add('claim', 'hidden_entity', array(
                'entity' => 'SDServiceDeskBundle:Claim',
            ))
            ->add('files', 'collection', array(
                'required' => false,
                'type'=> new ClaimMessageFileForm(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty'=> true
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\ClaimMessage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'claimMessageForm';
    }
}
