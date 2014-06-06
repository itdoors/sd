<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class HandlingDogovorType
 */
class HandlingDogovorType extends AbstractType
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
        $container = $this->container;

        $router = $container->get('router');

        $builder
            ->add('handlingId', 'itdoors_select2', array(
            'attr' => array(
                'class' => 'itdoors-select2 can-be-reseted submit-field',
                'class_outer' => 'col-md-4',
                'data-url'  => $router->generate('sd_common_ajax_handling'),
                'data-params' => json_encode(array(
                    'minimumInputLength' => 2,
                    'allowClear' => true,
                    'width' => '300px',
                    )),
                'placeholder' => 'Enter Handling'
                )
            ));

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                if ($data) {
                    if (!$data['handlingId']) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Handling cant be empty", array(), 'ListsHandlingBundle');

                        $form->addError(new FormError($msg));
                    }
                }
            }
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsDogovorBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingDogovorType';
    }
}
