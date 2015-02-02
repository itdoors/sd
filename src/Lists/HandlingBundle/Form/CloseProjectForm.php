<?php

namespace Lists\HandlingBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\Translator;

/**
 * CloseProjectForm
 */
class CloseProjectForm extends AbstractType
{
    protected $em;
    protected $router;
    protected $translator;

    /**
     * __construct
     *
     * @param EntityManager $em
     * @param Router        $router
     * @param Translator    $translator
     */
    public function __construct(EntityManager $em, Router $router, Translator $translator)
    {
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reasonClosed', 'textarea');

        $translator = $this->translator;
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($translator) {
                $form = $event->getForm();
                
                $reasonClosed = $form->get('reasonClosed')->getData();
                $projectId = $form->get('projectId')->getData();
                
                $project = $this->em->getRepository('ListsHandlingBundle:Handling')->find($projectId);

                if ($reasonClosed === null) {
                    $form->get('reasonClosed')->addError(
                        new FormError(
                            $translator->trans('The field can not be empty', array(), 'ListsHandlingBundle')
                        )
                    );
                } elseif (!$project) {
                    $form->addError(
                        new FormError(
                            $translator->trans('Project don`t found', array(), 'ListsHandlingBundle')
                        )
                    );
                } elseif ($project->isGosTender()) {
                    $typeProtocolOpen = $this->em->getRepository('ListsHandlingBundle:ProjectFileType')
                        ->findOneBy(array(
                            'alias' => 'protocol_open'
                        ));
                    $typeAcceptance = $this->em->getRepository('ListsHandlingBundle:ProjectFileType')
                        ->findOneBy(array(
                            'alias' => 'acceptance'
                        ));
                    $fileProtocolOpen = $this->em->getRepository('ListsHandlingBundle:ProjectFile')
                        ->findOneBy(array(
                            'type' => $typeProtocolOpen,
                            'project' => $project
                        ));
                    $fileAcceptance = $this->em->getRepository('ListsHandlingBundle:ProjectFile')
                        ->findOneBy(array(
                            'type' => $typeAcceptance,
                            'project' => $project
                        ));
                    if (!$fileProtocolOpen) {
                        $form->addError(
                            new FormError(
                                $translator->trans('Download please', array(), 'ListsHandlingBundle')
                                .': "'.$typeProtocolOpen->getName().'"'
                            )
                        );
                    }
                    if (!$fileAcceptance) {
                        $form->addError(
                            new FormError(
                                $translator->trans('Download please', array(), 'ListsHandlingBundle')
                                .': "'.$typeAcceptance->getName().'"'
                            )
                        );
                    }
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'closeProjectForm';
    }
}
