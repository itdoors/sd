<?php

namespace ITDoors\PayMasterBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * PayMasterNewForm
 */
class PayMasterNewForm extends AbstractType
{
    protected $em;
    protected $router;

    /**
     * __construct
     *
     * @param EntityManager $em
     * @param Router        $router
     */
    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $organization = $this->em->getRepository('ListsOrganizationBundle:Organization');

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
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
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
            ->add('customer', 'itdoors_select2_entity', array(
                'class' => 'ListsOrganizationBundle:Organization',
                'required' => true,
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $this->router->generate('lists_organization_ajax_search'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_organization_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        )),
                    'placeholder' => 'Enter customer'
                )
            ))
            ->add('contractor', 'itdoors_select2_entity', array(
                'class' => 'ListsOrganizationBundle:Organization',
                'required' => true,
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
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
            ->add('dogovor', 'itdoors_select2_dependent', array(
                'entity' => 'ListsDogovorBundle:Dogovor',
                'required' => true,
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
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
            ->add('delay', 'itdoors_dependent_listener_select2', array(
                'mapped' => false,
                'attr' => array(
                    'data-field' => 'paymentDeferment',
                    'data-dependent' => 'payMasterNewForm_dogovor',
                    'data-url'  => $this->router->generate('lists_dogovor_ajax_get_field')
                )
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
            ->add('mpks', 'itdoors_select2_entity', array(
                'class' => 'ListsMpkBundle:Mpk',
                'mapped' => false,
                'required' => true,
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $this->router->generate('lists_mpk_ajax_search'),
                    'data-url-by-id' => $this->router->generate('lists_mpk_ajax_mpk_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
//                        'multiple' => true,
                        )),
                    'placeholder' => 'Enter mpk'
                )
            ))
            ->add('invoiceAmount', 'money')
            ->add('vat', 'checkbox', array(
                'required' => false
            ))
            ->add('description', 'textarea');
        $builder
            ->add('create', 'submit');
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
