<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class CurrentAccountForm
 */
class CurrentAccountForm extends AbstractType
{
    protected $router;

    /**
     * __construct
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('typeAccount', 'entity', array(
                'class' => 'ListsOrganizationBundle:OrganizationCurrentAccountType',
                'required' => true,
                'empty_value' => ''
            ))
            ->add('organization', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\Organization',
                'empty_value' => '',
                'required' => true,
                'disabled' => true
            ))
            ->add('bank', 'hidden_entity', array(
                'entity'=>'Lists\OrganizationBundle\Entity\Bank'
            ))
            ->add('bankSearch', 'itdoors_select2', array(
                'mapped' =>false,
                'attr' => array(
                    'class' => 'form-control can-be-reseted submit-field',
                    'data-url' => $this->router->generate('lists_organization_ajax_bank_searh_name_and_mfo'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_bank_name_and_mfo_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    ))
                )
            ))
            ->add('cancel', 'submit');
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $payMaster = $event->getData();
                $form = $event->getForm();
                $bankId = $form->get('bankSearch')->getData();
                if (is_numeric($bankId)) {
//                    $bank = 
//                    $form->get('delay')->addError(new FormError($translator->trans('Postponement is incorrect', array(), 'ITDoorsPayMasterBundle')));
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
            'data_class' => 'Lists\OrganizationBundle\Entity\OrganizationCurrentAccount',
            'translation_domain' => 'ListsOrganizationBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'currentAccountForm';
    }
}
