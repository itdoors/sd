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
        $builder->add('userId', 'text', array(
            'attr' => array(
                'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                'data-url' => $router->generate('sd_common_ajax_user_fio'),
                'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                'data-params' => json_encode(array(
                    'minimumInputLength' => 0,
                    'allowClear' => true
                )),
                'placeholder' => 'Enter fio'
            )
        ))
            ->add('departmentId', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'data-url' => $router->generate('sd_common_ajax_departments_by_city_id'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter fio'
                )
        ))
            ->add('actionDate', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
        ))
            ->add('reportDate', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
        ))
            ->add('title', 'text', array())
            ->add('companyList', 'hidden', array(
                'required' => false,
                'mapped' => false
        ))
            ->add('actionType', 'entity', array(
                'class' => 'Lists\CoachBundle\Entity\ActionType',
                'property' => 'title',
                'empty_value' => '',
                'mapped' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                     'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    )))
        ))
        ->add('actionTopic', 'entity', array(
                'class' => 'Lists\CoachBundle\Entity\ActionTopic',
                'property' => 'title',
                'empty_value' => '',
                'mapped' => false,
                'multiple' => false,
                'required' => true,
                'attr' => array(
                        'data-params' => json_encode(array(
                                        'minimumInputLength' => 0,
                                        'allowClear' => true
                        )))
        ))
            ->add('city', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'data-url' => $router->generate('sd_common_ajax_city'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter fio'
                )
        ))
            ->add('text', 'textarea', array());
    }

    /**
     * @param OptionsResolverInterface $resolver            
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ArticleBundle\Entity\Article',
            'validation_groups' => array(
                'new'
            ),
            'translation_domain' => 'ListsArticleBundle'
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
