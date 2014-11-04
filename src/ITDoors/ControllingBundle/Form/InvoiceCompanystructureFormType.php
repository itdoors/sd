<?php

namespace ITDoors\ControllingBundle\Form;

use ITDoors\ControllingBundle\Entity\Invoice;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * InvoiceCompanystructureFormType
 */
class InvoiceCompanystructureFormType extends AbstractType
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
//        $container = $this->container;

        /** @var \ITDoors\ControllingBundle\Entity\InvoiceRepository $lr */
//        $lr = $container->get('it_doors_controlling.repository');

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
            'data_class' => 'ITDoors\ControllingBundle\Entity\InvoiceCompanystructure',
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoiceCompanystructureForm';
    }
}
