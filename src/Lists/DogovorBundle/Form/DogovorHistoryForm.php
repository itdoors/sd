<?php

namespace Lists\DogovorBundle\Form;

use Lists\DogovorBundle\Entity\Dogovor;
use Lists\DogovorBundle\Entity\DogovorRepository;
use Lists\DogovorBundle\Entity\DopDogovorRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DogovorHistoryForm
 */
class DogovorHistoryForm extends AbstractType
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * __construct
     */
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
        $container = $this->container;

        $builder
            ->add('submit', 'submit')
            ->add('cancel', 'button');

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) use ($container)
            {
                /** @var DopDogovorRepository $ddr */
                $ddr = $container->get('lists_dogovor.dopdogovor.repository');

                /** @var Dogovor $data */
                $data = $event->getData();

                $form = $event->getForm();

                $form->add('dogovorId', 'hidden', array(
                    'data' => $data->getId(),
                    'mapped' => false
                ));

                if ($data)
                {
                    if (!$data->getProlongation())
                    {
                        $form->add('dopDogovor', 'entity', array(
                            'class' => 'ListsDogovorBundle:DopDogovor',
                            'empty_value' => '',
                            'required' => false,
                            'query_builder' => $ddr->getDopDogovorQueryByDogovorId($data->getId()),
                            'mapped' => false
                        ));
                    }

                    $prolongationDefaultDate =
                            $data->getProlongationDate()
                            ? $data->getProlongationDate()
                            : $data->getStopdatetime();

                    $prolongationTerm = intval($data->getProlongationTerm());

                    if ($prolongationTerm && $data->getProlongation())
                    {
                        $prolongationDefaultDate->add(new \DateInterval("P{$prolongationTerm}D"));
                    }

                    $form->add('prolongationDateTo', 'datetime', array(
                        'data' => $prolongationDefaultDate,
                        'widget' => 'single_text',
                        'format' => 'dd.MM.yyyy',
                        'mapped' => false
                    ));
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) use ($container)
            {
                /** @var DogovorRepository $dr */
                $dr = $container->get('lists_dogovor.repository');

                $translator = $container->get('translator');

                /** @var Dogovor $data */
                $data = $event->getData();

                $form = $event->getForm();

                /** @var Dogovor $dogovor */
                $dogovor = $dr->find($data['dogovorId']);

                $prolongationDateTo = new \DateTime($data['prolongationDateTo']);

                if ($dogovor->getStopdatetime() > $prolongationDateTo)
                {
                    $msg = $translator->trans("Stopdate can't greater then prolongation date", array(), 'ListsDogovorBundle');

                    $form->addError(new FormError($msg));
                }

                if (!$dogovor->getProlongation())
                {
                    if (!isset($data['dopDogovor']) || !$data['dopDogovor'])
                    {
                        $msg = $translator->trans("You must pick dop dogovor for plonongation",
                            array(),
                            'ListsDogovorBundle'
                        );

                        $form->addError(new FormError($msg));
                    }
                }

                //$form->is
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
            'validation_groups' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dogovorHistoryForm';
    }
}
