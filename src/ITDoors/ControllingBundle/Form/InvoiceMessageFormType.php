<?php

namespace ITDoors\ControllingBundle\Form;

use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoiceMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * InvoiceMessageFormType
 */
class InvoiceMessageFormType extends AbstractType
{

    protected $container;

    /**
     *  __construct
     * 
     * @param obj $container Description
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param object $builder desc
     * @param array  $options desc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;

        $builder
            ->add('note', 'textarea', array(
                'required' => false,
                'mapped' => false
            ))
            ->add('file', 'file', array(
                'required' => false
            ));

        /** @var User $user */
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
            ->add('user', 'hidden_entity', array(
                'entity' => 'SDUserBundle:User',
                'data_class' => null,
                'data' => $user
            ))
            ->add('createdate', 'hidden');
        $builder
            ->add('create', 'submit')
            ->add('cancel', 'button');

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ITDoors\ControllingBundle\Entity\InvoiceMessage',
            'translation_domain' => 'ITDoorsControllingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoiceMessageForm';
    }
}
