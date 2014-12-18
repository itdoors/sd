<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManager;

/**
 * Class CurrentAccountForm
 */
class CurrentAccountForm extends AbstractType
{
    protected $router;
    protected $em;

    /**
     * __construct
     *
     * @param Router $router
     * @param EntityManager $em
     */
    public function __construct(Router $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;
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
                'entity'=>'Lists\OrganizationBundle\Entity\Bank',
                'required' => true
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
                $form = $event->getForm();
                $data = $event->getData();
                $bankId = $form->get('bankSearch')->getData();
                $bank = $this->em->getRepository('ListsOrganizationBundle:Bank')->find($bankId);
                $organizationId = $form->get('organizationId')->getData();
                $organization = $this->em->getRepository('ListsOrganizationBundle:Organization')->find($organizationId);
                $data->setBank($bank);
                $data->setOrganization($organization);
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
