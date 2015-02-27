<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
Use Symfony\Component\Translation\Translator;
use Lists\ProjectBundle\Form\ProjectBaseForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class ProjectSimpleForm
 */
class ProjectSimpleForm extends ProjectBaseForm
{
    protected $em;
    protected $router;
    protected $translator;

    /**
     * @param EntityManager $em
     * @param Router        $router
     * @param Translator    $translator
     */
    public function __construct(EntityManager $em, Router $router, Translator $translator)
    {
        parent::__construct($em, $router, $translator);
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('createDate', 'date', array(
                'widget' => 'single_text',
                'input'  => 'datetime',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control',
                    'class_outer' => 'col-md-3'
                ),
                'required' => true
            ))
            ->add('services', 'entity', array(
                'class' => 'ListsProjectBundle:ServiceProjectSimple',
                'empty_value' => '',
                'multiple' => 'multiple',
                'query_builder' => function (\Lists\ProjectBundle\Entity\ServiceProjectSimpleRepository $repository) {
                    return $repository->createQueryBuilder('s')
                        ->orderBy('s.sortorder', 'ASC');
                },
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    ))
                )
            ))
            ->add('pf', 'text', array(
                'required' => false
            ))
            ->add('summaWithVAT', 'text', array(
                'required' => false
            ))
            ->add('type', 'choice', array(
                'mapped' => false,
                'required' => true,
                'empty_value' => '',
                'choices' => array(
                    'simple' => 'Negotiation procedure',
                    'commercial_tender' => 'Commercial tender',
                    'electronic_trading' => 'Electronic trading'
                ),
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    ))
                )
            ))
            ->add('create', 'submit');
        $translator = $this->translator;
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($translator) {
                $form = $event->getForm();
                $type = $form->get('type')->getData();
                if (empty($type)) {
                    $form->get('type')->addError(
                        new FormError(
                            $translator->trans('The field can not be empty', array(), 'ITDoorsPayMasterBundle')
                        )
                    );
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
            'data_class' => 'Lists\ProjectBundle\Entity\ProjectSimple',
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsProjectBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'projectSimpleForm';
    }
}
