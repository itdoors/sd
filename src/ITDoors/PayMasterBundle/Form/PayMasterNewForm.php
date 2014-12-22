<?php

namespace ITDoors\PayMasterBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\Translator;

/**
 * PayMasterNewForm
 */
class PayMasterNewForm extends AbstractType
{
    protected $em;
    protected $router;
    protected $translator;

    /**
     * __construct
     *
     * @param EntityManager $em
     * @param Router        $router
     * @param Translator    $translator
     */
    public function __construct(EntityManager $em, Router $router, Translator $translator)
    {
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('scan', 'file')
            ->add('invoiceDate', 'itdoors_date_decade', array(
                    'widget' => 'single_text',
                    'format' => 'dd.M.yyyy'
            ))
            ->add('expectedDate', 'itdoors_date_decade', array(
                    'widget' => 'single_text',
                    'format' => 'dd.M.yyyy'
            ))
            ->add('payer', 'itdoors_select2_entity', array(
                'class' => 'ListsOrganizationBundle:Organization',
                'required' => true,
                'attr' => array(
                    'required' => true,
                    'aria-required' => 'true',
                    'class' => 'can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $this->router->generate('lists_organization_ajax_organization_search_signs'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_organization_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => false,
                    )),
                    'placeholder' => 'Enter payer'
                )
            ))
            ->add('customers', 'entity', array(
                'class' => 'ListsOrganizationBundle:Organization',
                'property' => 'name',
                'empty_value' => '',
                'multiple' => true,
                'attr' => array(
                    'class' => 'form-control itdoors-select2',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter customer',
                )
            ))
            ->add('contractor', 'itdoors_select2_entity', array(
                'class' => 'ListsOrganizationBundle:Organization',
                'required' => true,
                'attr' => array(
                    'class' => 'can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $this->router->generate('lists_organization_ajax_search'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_organization_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        )),
                    'placeholder' => 'Enter payer'
                )
            ))
            ->add('contractorEdrpou', 'text', array(
                'required' => true,
                'mapped' => false
            ))
            ->add('dogovor', 'itdoors_select2_dependent', array(
                'entity' => 'ListsDogovorBundle:Dogovor',
                'required' => true,
                'attr' => array(
                    'class' => 'can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-dependent' => 'payMasterNewForm_contractor',
                    'data-url'  => $this->router->generate('lists_dogovor_ajax_search_dependent'),
                    'data-url-by-id' => $this->router->generate('lists_dogovor_ajax_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        )),
                    'placeholder' => 'Enter dogovor'
                )
            ))
            ->add('delay', 'text', array(
                'mapped' => false
            ))
            ->add('dogovorFile', 'itdoors_dependent_listener_select2', array(
                'mapped' => false,
                'attr' => array(
                    'data-field' => 'webPath',
                    'data-dependent' => 'payMasterNewForm_dogovor',
                    'data-url'  => $this->router->generate('lists_dogovor_ajax_get_field')
                )
            ))
            ->add('currentAccount', 'itdoors_select2_dependent', array(
                'entity' => 'ListsOrganizationBundle:OrganizationCurrentAccount',
                'required' => true,
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-dependent' => 'payMasterNewForm_contractor',
                    'data-url'  => $this->router->generate('lists_organization_ajax_search_current_account_dependent'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_current_account_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        )),
                    'placeholder' => 'Enter current account'
                )
            ))
            ->add('mfo', 'itdoors_dependent_listener_select2_to_select2', array(
                'mapped' => false,
                'required' => true,
                'attr' => array(
                    'data-field' => 'mfo',
                    'data-dependent' => 'payMasterNewForm_currentAccount',
                    'data-url'  => $this->router->generate('lists_organization_ajax_bank_searh_dependent_field'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_bank_by_id'),
                    'data-url-by-one' => $this->router->generate('lists_organization_ajax_bank_by_one'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        )),
                    'placeholder' => 'Enter mfo'
                )
            ))
            ->add('bank', 'itdoors_dependent_listener_select2', array(
                'mapped' => false,
                'attr' => array(
                    'data-field' => 'name',
                    'data-dependent' => 'payMasterNewForm_mfo',
                    'data-url'  => $this->router->generate('lists_organization_ajax_bank_get_field'),
                    'placeholder' => 'Enter name'
                )
            ))
            ->add('mpks', 'entity', array(
                'class' => 'ListsMpkBundle:Mpk',
                'property' => 'name',
                'empty_value' => '',
                'multiple' => true,
                'attr' => array(
                    'class' => 'form-control itdoors-select2',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter mpk',
                )
            ))
            ->add('invoiceAmount', 'money')
            ->add('vat', 'itdoors_choice', array(
                'required' => true,
                'empty_value' => '',
                'attr' => array(
                    'class' => 'form-control can-be-reseted',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0
                    )),
                    'placeholder' => 'Select vat',
                ),
                'choices' => array(
                    '1' => 'With vat',
                    '0' => 'VAT not included'
                    )
                ))
            ->add('description', 'textarea');
        $builder
            ->add('create', 'submit');

        $translator = $this->translator;
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($translator) {
                $payMaster = $event->getData();
                $form = $event->getForm();
                if ($form->get('delay')->getData() != $payMaster->getDogovor()->getPaymentDeferment()) {
                    $form->get('delay')->addError(new FormError($translator->trans('Postponement is incorrect', array(), 'ITDoorsPayMasterBundle')));
                }
                if ($form->get('contractorEdrpou')->getData() != $payMaster->getContractor()) {
                    $form->get('contractorEdrpou')->addError(new FormError($translator->trans('Edrpou set not true', array(), 'ITDoorsPayMasterBundle')));
                }
                if (strpos($form->get('currentAccount')->getData(), 'isNew_') !== false) {
                    $name = explode('isNew_', $form->get('currentAccount')->getData());
                    $organization = $this->em->getRepository('ListsOrganizationBundle:Organization')->find($form->get('contractor')->getData());
                    $type = $this->em->getRepository('ListsOrganizationBundle:OrganizationCurrentAccountType')->find(2);
                    $bank = $this->em->getRepository('ListsOrganizationBundle:Bank')->find($form->get('mfo')->getData());
                    $currentAccount = new \Lists\OrganizationBundle\Entity\OrganizationCurrentAccount();
                    $currentAccount->setBank($bank);
                    $currentAccount->setName($name[1]);
                    $currentAccount->setOrganization($organization);
                    $currentAccount->setTypeAccount($type);
                    $this->em->persist($currentAccount);
                    $this->em->flush();
                    $form->get('currentAccount')->setData($currentAccount);
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ITDoors\PayMasterBundle\Entity\PayMaster',
            'validation_groups' => array('new'),
            'translation_domain' => 'ITDoorsPayMasterBundle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'payMasterNewForm';
    }
}
