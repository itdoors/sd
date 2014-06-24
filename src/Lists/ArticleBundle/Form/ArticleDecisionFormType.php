<?php

namespace Lists\ArticleBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

/**
 * Class ArticleDecisionFormType
 */
class ArticleDecisionFormType extends AbstractType
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
        $router = $this->container->get('router');
        $builder
            ->add('userId', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter fio'
                )
            ))
            ->add('dateUnpublick', 'text', array())
            ->add('title', 'text', array())
            ->add('text', 'textarea', array());

        $builder->add('users', 'text', array(
                'mapped' => false,
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'data-url' => $router->generate('sd_common_ajax_user_fio_not_my'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter fio'
                )
            ));
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();
                $form = $event->getForm();
                $formData = $container->get('request')->get($form->getName());

                /** @var Translator $translator*/
                $translator = $container->get('translator');

                if (count(explode(',', $formData['users'])) < 1) {
                    $msg = $translator->trans("You need to add at least 1 members", array(), 'ListsArticleBundle');
                    $form->get('users')->addError(new FormError($msg));
                }
                if (in_array($data->getUserId(), explode(',', $formData['users']))) {
                    $msg = $translator->trans("You can not add website", array(), 'ListsArticleBundle');
                    $form->get('users')->addError(new FormError($msg));
                }
                $dateTime = new \DateTime($data->getDateUnpublick());
                if ($dateTime->getTimestamp() < time()) {
                    $msg = $translator->trans(
                        "Date can not be less than the start date of",
                        array(),
                        'ListsArticleBundle'
                    );
                    $form->get('dateUnpublick')->addError(new FormError($msg));
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
            'data_class' => 'Lists\ArticleBundle\Entity\Article',
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsArticleBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'articleDecisionForm';
    }
}
