<?php

namespace SD\DashboardBundle\Form;
use SD\CalendarBundle\Services\CalendarService;
use SD\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * Dasboard calendar filter form
 */
class DashboardCalendarFilterType extends AbstractType
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct
     *
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
        /** @var CalendarService $cs*/
        $cs = $this->container->get('sd_calendar.service');

        /** @var User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();

        /** @var Router $router */
        $router = $this->container->get('router');

        $builder
            ->add('eventType', 'choice', array(
                'choices' => $cs->getDashboardEventChoices(),
                'empty_value' => '',
                'attr' => array(
                    'class' => 'sd-select2 can-be-reseted',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'width' => '200px'
                    )),
                    'placeholder' => 'Last Events'
                ),

            ));

        if ($user->hasRole('ROLE_SALESADMIN'))
        {
            $builder
                ->add('userIds', 'hidden', array(
                    'attr' => array(
                        'class' => 'sd-select2 can-be-reseted',
                        'data-url'  => $router->generate('sd_common_ajax_user'),
                        'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                        'data-params' => json_encode(array(
                            'minimumInputLength' => 2,
                            'allowClear' => true,
                            'width' => '200px',
                            'multiple' => true
                        )),
                        'placeholder' => 'Enter Manager'
                    )
                ));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'SDCalendarBundle'
        ));
    }

    /**
     * @{@inheritDoc}
     */
    public function getName()
    {
        return 'dashboardCalendarFilterType';
    }
}