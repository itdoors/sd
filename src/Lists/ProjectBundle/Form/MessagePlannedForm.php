<?php

namespace Lists\ProjectBundle\Form;


use Symfony\Component\Form\FormBuilderInterface;
use Lists\ProjectBundle\Form\MessageCurrentForm;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class MessagePlannedForm
 */
class MessagePlannedForm extends MessageCurrentForm
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('eventDatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy HH:mm'
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ProjectBundle\Entity\MessagePlanned',
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsProjectBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'messagePlannedForm';
    }
}
