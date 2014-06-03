<?php

namespace ITDoors\EmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AutomailerType
 *
 * @package ITDoors\EmailBundle\Form
 */
class AutomailerType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromEmail')
            ->add('fromName', 'text', array(
                'required' => false
            ))
            ->add('toEmail')
            ->add('subject', 'text')
            ->add('body', 'textarea');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TSS\AutomailerBundle\Entity\Automailer',
            'translation_domain' => 'ITDoorsEmailBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'itdoors_emailbundle_automailertype';
    }
}
