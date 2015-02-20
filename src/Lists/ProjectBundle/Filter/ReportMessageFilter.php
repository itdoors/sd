<?php

namespace Lists\ProjectBundle\Filter;

use Symfony\Component\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ReportMessageFilter
 */
class ReportMessageFilter extends AbstractType
{
    protected $router;
    /**
     * __construct
     * 
     * @param Router $router
     */
    public function __construct (Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromDate', 'datetime', array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('toDate', 'datetime', array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('managers', 'text', array(
                'attr' => array(
                    'requare' => false,
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
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
            'csrf_protection'   => false,
            'translation_domain' => 'ListsProjectBundle',
            'validation_groups' => array('filtering')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'reportMessageFilter';
    }
}
