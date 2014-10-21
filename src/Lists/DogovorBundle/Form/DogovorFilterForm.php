<?php

namespace Lists\DogovorBundle\Form;

use Lists\DogovorBundle\Entity\DogovorRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DogovorFilterForm
 */
class DogovorFilterForm extends AbstractType
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->container->get('lists_dogovor.repository');

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

//        $translator = $this->container->get('translator');

        $builder
            ->add('number')
            ->add('organization', 'hidden')
            ->add('subject', 'text')
            ->add('customer', 'hidden')
            ->add('performer', 'hidden')
            ->add('prolongation', 'choice', array(
                'choices'   => $dr->getProlongationChoices(),
                'empty_value' =>  ''
            ))
            ->add('dogovorType', 'entity', array(
                'class' => 'ListsLookupBundle:Lookup',
                'query_builder' => $lr->getOnlyDogovorTypeQuery(),
                'empty_value' =>  ''
            ))
            ->add('typeDate', 'choice', array(
                'choices'   => array(
                    'startdatetime' => 'Startdatetime',
                    'stopdatetime' => 'Stopdatetime',
                    'prologation' => 'Prolongation Date',
                ),
                'empty_value' =>  ''
            ))
            ->add('dateRangeForType', 'text');

        $builder
            ->add('save', 'submit')
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
            'translation_domain' => 'ListsDogovorBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dogovorFilterForm';
    }
}
