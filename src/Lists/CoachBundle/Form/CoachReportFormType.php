<?php
namespace Lists\CoachBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CoachReportFormType
 */
class CoachReportFormType extends AbstractType
{

    protected $container;

    /**
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
        $router = $this->container->get('router');
        $builder
            ->add('action', new ActionFormType($this->container))
            ->add('author', 'itdoors_select2', array(
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 0,
                                    'allowClear' => true
                    ))
                )
            ))
            ->add('created', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
        ))
            ->add('title', 'text', array())
            ->add('city', 'itdoors_select2', array(
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $router->generate('sd_common_ajax_city'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_by_id'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 0,
                                    'allowClear' => true,
                    ))
                )
        ))
            ->add('text', 'textarea', array(
                'required' => false
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver            
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\CoachBundle\Entity\CoachReport',
            'validation_groups' => array(
                'new'
            ),
            'translation_domain' => 'ListsCoachBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'coachReportForm';
    }
}
