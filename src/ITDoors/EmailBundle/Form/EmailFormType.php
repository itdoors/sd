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

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $container->get('it_doors_controlling.repository');

        $builder
            ->add('note', 'textarea', array(
                'required' => false,
                'mapped' => false
            ));

        /** @var User $user */
        $user = $container->get('security.context')->getToken()->getUser();
//
//        if ($user->hasRole('ROLE_SALESADMIN'))
//        {
        $builder
            ->add('user', 'hidden_entity', array(
                'entity' => 'SDUserBundle:User',
                'data_class' => null,
                'data' => $user
            ))
            ->add('createdate', 'hidden');
//        }
        $builder
            ->add('create', 'submit')
            ->add('cancel', 'button');

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();
                $form = $event->getForm();
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                $currentDatetime = new \DateTime($data['createdate']);

                if (isset($data['invoice_id']) && $data['invoice_id']) {
                    $invoiceId = $data['invoice_id'];
                }
            }
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ITDoors\ControllingBundle\Entity\InvoiceMessage',
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsHandlingBundle'
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
