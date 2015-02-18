<?php
namespace Lists\ProjectBundle\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * ProjectFilter
 */
class ProjectFilter extends AbstractType
{
    /**
     * __construct
     * 
     * @param Router $router
     */
    public function __construct (Router $router)
    {
        $this->router = $router;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organization', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $this->router->generate('sd_common_ajax_organization_for_contacts'),
                    'data-url-by-id' => $this->router->generate('sd_common_ajax_organization_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '100%',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter organiztion',
                )
            ))
            ->add('managers', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $this->router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $this->router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '100%',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter manager',
                )
            ))
            ;
//        $builder->add('id', 'filter_entity', array(
//            'class' => 'Main\ErrorBundle\Entity\ItdJsError',
//            'property' => 'url',
//            'multiple' => false,
//            'attr' => array(
//                'data-params' => json_encode(array(
//                    'minimumInputLength' => 2,
//                    'allowClear' => true
//                )),
//                'style' => 'width:100%',
//                'class' => 'form-control'
//            )
//        ));
//        $builder->add('createDatetime', 'filter_date');
//        $builder->add('message', 'filter_text');
        
        $builder
            ->add('submit', 'submit')
            ->add('reset', 'reset');
    
    }

    public function getName()
    {
        return 'projectFilter';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'translation_domain' => 'ListsProjectBundle',
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}