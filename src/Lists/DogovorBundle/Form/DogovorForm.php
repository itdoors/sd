<?php

namespace Lists\DogovorBundle\Form;

use Lists\DogovorBundle\Entity\Dogovor;
use Lists\DogovorBundle\Entity\DogovorRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DogovorForm
 */
class DogovorForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

        /** @var DogovorRepository $dr */
        $dr = $this->container->get('lists_dogovor.repository');

        $builder
            ->add('customer', 'hidden_entity', array(
                'entity' => 'ListsOrganizationBundle:Organization',
                'required' => true,
            ))
            ->add('performer', 'hidden_entity', array(
                'entity' => 'ListsOrganizationBundle:Organization',
                'required' => true,
            ))
            //->add('name')
            ->add('number')
            ->add('subject')
            ->add('startdatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('stopdatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('launchdate', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('prolongation')
            ->add('prolongationTerm')
            ->add('prolongationDate', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('dogovorType', null, array(
                'query_builder' => $lr->getOnlyDogovorTypeQuery()
            ))
            ->add('city', 'hidden_entity', array(
                'entity' => 'ListsCityBundle:City',
            ))
            ->add('companystructure')
            ->add('saller', 'hidden_entity', array(
                'entity' => 'SDUserBundle:User'
            ))
            ->add('total')
            ->add('summMonthVat')
            ->add('plannedPf1')
            ->add('plannedPf1Percent')
            ->add('paymentDeferment')
            ->add('file', 'file', array(
                'required' => false
            ))
            ->add('mashtab', 'choice', array(
                'choices'   => $dr->getMashtabChoices(),
                'empty_value' => false
            ))
            ->add('isActive', null, array(
                'data' => true
            ));

        $builder
            ->add('create', 'submit');

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                /** @var Dogovor $data */
                $data = $event->getData();

                $form = $event->getForm();

                $translator = $container->get('translator');

                $customer = $data->getCustomer();

                $performer = $data->getPerformer();

                if ($data->getStartdatetime() >= $data->getStopdatetime() && $data->getStartdatetime()) {
                    $msgString = "Start date can't be greater then stop date";

                    $msg = $translator->trans($msgString, array(), 'ListsDogovorBundle');

                    $form->addError(new FormError($msg));
                }
                if ($customer && $customer->getEdrpou() == '') {
                    $msgString = "Customer dont have edrpou";

                    $msg = $translator->trans($msgString, array(), 'ListsOrganizationBundle');

                    $form->addError(new FormError($msg));
                }
                if ($performer && $performer->getEdrpou() == '') {
                    $msgString = "Performer dont have edrpou";

                    $msg = $translator->trans($msgString, array(), 'ListsOrganizationBundle');

                    $form->addError(new FormError($msg));
                }
                if (
                        $performer
                        &&
                        $performer->getEdrpou() !== ''
                        &&
                        $customer
                        &&
                        $customer->getEdrpou() !== ''
                        &&
                        $data->getNumber()
                        &&
                        $data->getStartdatetime()
                    ) {
                    $em = $container->get('doctrine')->getManager();
                    $dogovor = $em->getRepository('ListsDogovorBundle:Dogovor')
                        ->findBy(array(
                            'customer' => $customer,
                            'performer' => $performer,
                            'number' => $data->getNumber(),
                            'startdatetime' => $data->getStartdatetime()
                        ));
                    if ($dogovor) {
                        $msgString = "This document has already been added";

                        $msg = $translator->trans($msgString, array(), 'ListsDogovorBundle');

                        $form->addError(new FormError($msg.' ID: '.$dogovor[0]->getId()));
                    }
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
            'data_class' => 'Lists\DogovorBundle\Entity\Dogovor',
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsDogovorBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dogovorForm';
    }
}
