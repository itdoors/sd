<?php

namespace SD\UserBundle\Form;

use SD\UserBundle\Entity\Stuff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * StuffFilterForm
 */
class StuffFilterForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * __construct
     * 
     * @param type $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var DogovorRepository $u */
        //$u = $this->container->get('sd_user.repository');

        $translator = $this->container->get('translator');

        $router = $this->container->get('router');

        $builder
            ->add('firstName', 'text', array(
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
                    'placeholder' => 'Enter fio',
                )
            ))
            ->add('mobilephone', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_contact_phone'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter phone',
                )
            ))
            ->add('email', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_user_email'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter email',
                )
            ))
            ->add('position', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_user_position'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter position',
                )
            ))
            ->add('company', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_contact_company'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter subdivision',
                )
            ))
            ->add('isActive', 'choice', array(
                'attr' => array(
                    'class' => 'form-control select2 input-middle',
                    'placeholder' => 'Status'
                ),
                'choices' => array(
                    'active' => $translator->trans("Active", array(), 'SDUserBundle.ru'),
                    'blocked' => $translator->trans("Blocked", array(), 'SDUserBundle.ru'),
                ),
                'empty_value' => ''
            ))
            ->add('isFired', 'choice', array(
                'attr' => array(
                    'class' => 'form-control select2 input-middle',
                    'placeholder' => 'Fired'
                ),
                'choices' => array(
                    'fired' => $translator->trans("Fired", array(), 'SDUserBundle.ru'),
                    'No fired' => $translator->trans("No fired", array(), 'SDUserBundle.ru'),
                ),
                'empty_value' => ''
            ))
            ->add('submit', 'submit')
            ->add('reset', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'SDUserBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'stuffFilterForm';
    }
}
