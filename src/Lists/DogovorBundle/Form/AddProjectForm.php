<?php

namespace Lists\DogovorBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class AddProjectForm
 */
class AddProjectForm extends AbstractType
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
            ->add('project', 'hidden_entity', array(
                'entity' => 'ListsProjectBundle:Project',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('lists_dogovor_ajax_search_project'),
                    'data-url-by-id' => $router->generate('lists_project_ajax_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 1,
                        'allowClear' => true
                    ))
                )
            ));

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                $translator = $container->get('translator');
                $data = $event->getData();

                $form = $event->getForm();

                /** @var \Lists\ProjectBundle\Entity\Project $project */
                $project = $form->get('project')->getData();
                if ($data) {
                    if (!$project) {
                        $msg = $translator->trans("Specify the project please", array(), 'ListsDogovorBundle');

                        $form->get('project')->addError(new FormError($msg));
                    } elseif ($project->getDogovor()) {
                        $msg = $translator->trans("The project is already associated with the contract", array(), 'ListsDogovorBundle');
                        $msg .= ' № '.$project->getDogovor()->getNumber();
                        $form->get('project')->addError(new FormError($msg));
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
            'translation_domain' => 'ListsDogovorBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_project_form';
    }
}
