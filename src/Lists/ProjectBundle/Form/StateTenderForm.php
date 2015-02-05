<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
Use Symfony\Component\Translation\Translator;

/**
 * Class StateTenderForm
 */
class StateTenderForm extends AbstractType
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
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organization', 'hidden_entity', array(
                'entity' => 'ListsOrganizationBundle:Organization',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $this->router->generate('lists_organization_ajax_search'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_organization_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'formatNoMatches'=> $this->translator->trans('Organization don`t found').
                            ' <a href="#" onclick="createOrganization()">'.$this->translator->trans('Create').'</a>'
                    ))
                )
            ))
             ->add('services', 'entity', array(
                'class' => 'ListsProjectBundle:service',
                'empty_value' => '',
                'multiple' => 'multiple',
                'query_builder' => function (\Lists\ProjectBundle\Entity\ServiceRepository $repository) {
                    return $repository->createQueryBuilder('s')
                        ->where('s.slug = :slug')
                        ->setParameter(':slug', "gos_tender")
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
            ->add('budget')
            ->add('square')
            ->add('description')
                
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
            'data_class' => 'Lists\HandlingBundle\Entity\ProjectGosTender',
            'validation_groups' => array('createTender'),
            'translation_domain' => 'ListsHandlingBundle',
            'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gosTenderForm';
    }
}
