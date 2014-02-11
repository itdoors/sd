<?php

namespace Lists\DogovorBundle\Form;

use Lists\DogovorBundle\Entity\DogovorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DogovorFilterForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var DogovorRepository $dr */
        $dr = $this->container->get('lists_dogovor.repository');

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

        $translator = $this->container->get('translator');

        $builder
            ->add('organization', 'hidden')
            ->add('customer', 'hidden')
            ->add('performer', 'hidden')
            ->add('prolongation','choice', array(
                'choices'   => $dr->getProlongationChoices(),
                'empty_value' =>  ''
            ))
            ->add('dogovorType', 'entity', array(
                'class' => 'ListsLookupBundle:Lookup',
                'query_builder' => $lr->getOnlyDogovorTypeQuery(),
                'empty_value' =>  ''
            ));


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
