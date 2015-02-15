<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Lists\HandlingBundle\Form\ProjectForm;
Use Symfony\Component\Translation\Translator;

/**
 * Class GosTenderForm
 */
class GosTenderForm extends AbstractType
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
            ->add('project', new ProjectForm($this->em, $this->router, $this->translator))
                
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
