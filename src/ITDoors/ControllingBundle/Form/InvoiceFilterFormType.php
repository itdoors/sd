<?php

namespace ITDoors\ControllingBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class InvoiceFilterFormType
 */
class InvoiceFilterFormType extends AbstractType
{

    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $this->lr = $this->container->get('lists_lookup.repository');

        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $this->ur = $this->container->get('sd_user.repository');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router = $this->container->get('router');

        $builder
            ->add('invoiceId', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_invoice_invoice_id'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_invoice_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '250px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter invoice number',
                )
            ))
            ->add('customer', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_organization_for_contacts'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '250px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter customer',
                )
            ))
            ->add('performer', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_organization_for_contacts'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '250px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter performer',
                )
            ))
            ->add('actNumber', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_invoice_act_number'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_invoice_by_act_numbers'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '250px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter number act',
                )
            ))
            ->add('companystructure', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_company_structure'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_company_structure_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '250px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter companystructure',
                )
            ))
            ->add('daterange', 'text', array(
                'attr' => array(
                    'class_outer' => 'input-group input-group itdoors-daterange-custom tooltips col-md-3 ',
                    'class' => 'form-control can-be-reseted itdoors-daterange-text ',
                    'placeholder' => 'Enter daterange',
                    'data-original-title' => 'Enter daterange',
                )
            ));

        $builder
            ->add('submit', 'submit')
            ->add('reset', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'ITDoorsControllingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoiceFilterForm';
    }
}
