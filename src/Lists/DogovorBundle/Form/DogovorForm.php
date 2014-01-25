<?php

namespace Lists\DogovorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DogovorForm extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            /*->add('startdatetime')
            ->add('stopdatetime')
            ->add('subject')
            ->add('filepath')
            ->add('isActive')
            ->add('mashtab')
            ->add('prolongation')
            ->add('number')
            ->add('total')
            ->add('maturity')
            ->add('completionNotice')
            ->add('paymentDeferment')
            ->add('prolongationTerm')
            ->add('launchDate')
            ->add('summMonthVat')
            ->add('plannedPf1')
            ->add('plannedPf1Percent')
            ->add('city')
            ->add('companyRole')
            ->add('companystructure')
            ->add('dogovorType')
            ->add('organization')
            ->add('stuff')
            ->add('user')*/
        ;

        $builder
            ->add('create', 'submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\DogovorBundle\Entity\Dogovor'
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
