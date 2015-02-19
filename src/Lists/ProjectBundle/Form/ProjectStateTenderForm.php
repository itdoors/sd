<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
Use Symfony\Component\Translation\Translator;
use Lists\ProjectBundle\Form\ProjectBaseForm;

/**
 * Class ProjectStateTenderForm
 */
class ProjectStateTenderForm extends ProjectBaseForm
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
            ->add('services', 'entity', array(
                'class' => 'ListsProjectBundle:ServiceProjectStateTender',
                'empty_value' => '',
                'multiple' => 'multiple',
                'query_builder' => function (\Lists\ProjectBundle\Entity\ServiceProjectStateTenderRepository $repository) {
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
            ->add('square')
            ->add('budget')
                
            ->add('vdz', 'text', array(
                'required' => true
            ))
            ->add('advert', 'integer', array(
                'required' => true
            ))
            ->add('typeOfProcedure', 'text', array(
                'required' => true
            ))
            ->add('place', 'text', array(
                'required' => true
            ))
            ->add('delivery', 'text', array(
                'required' => true
            ))
            ->add('datetimeDeadline', 'datetime', array(
                'widget' => 'single_text',
                'input'  => 'datetime',
                'format' => 'dd.MM.yyyy HH:mm',
                'attr' => array(
                    'class' => 'form-control',
                    'class_outer' => 'col-md-3'
                ),
                'required' => true
            ))
            ->add('datetimeOpening', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy HH:mm',
                'input'  => 'datetime',
                'attr' => array(
                    'class' => 'form-control',
                    'class_outer' => 'col-md-3'
                ),
                'required' => true
            ))
            ->add('software', 'text', array(
                'required' => false
            ))
            ->add('kveds', 'entity', array(
                'class' => 'ListsOrganizationBundle:Kved',
                'empty_value' => '',
                'multiple' => true,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    ))
                )
            ));

        $builder
            ->add('create', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ProjectBundle\Entity\ProjectStateTender',
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsProjectBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'projectStateTenderForm';
    }
}
