<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OrganizationSalesAdminFilterFormType
 */
class OrganizationSalesAdminFilterFormType extends OrganizationSalesDispatcherFilterFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $router = $this->container->get('router');

        $builder
            ->add('users', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter manager',
                )
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationSalesAdminFilterForm';
    }
}
