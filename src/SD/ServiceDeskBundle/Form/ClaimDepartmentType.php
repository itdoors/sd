<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class ClaimDepartmentType extends AbstractType
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
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router = $this->container->get('router');
        $builder
            ->add('organization', 'entity', array(
                'mapped' => false,
                'empty_data'  => null,
//                 'placeholder' => 'Choose an option',
                'class' => 'ListsOrganizationBundle:Organization'
            ))
            ->add('type', 'choice', array('choices'   => \SD\ServiceDeskBundle\Entity\ClaimType::values()))
            ->add('importance')
            ->add('text')
            ->add('targetDepartment', 'hidden_entity', array(
                'entity' => 'ListsDepartmentBundle:Departments',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_departments_by_city_id'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    ))
                )
            ))
            ->add('customer', 'hidden_entity', array(
                'entity' => 'SDBusinessRoleBundle:CompanyClient',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_departments_by_city_id'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    ))
                )
            ))
            ->add('files', 'collection', array(
                'required' => false,
                'type'=> new \SD\ServiceDeskBundle\Form\ClaimFileForm(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty'=> true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\ClaimDepartment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sd_servicedeskbundle_claimdepartment';
    }
}
