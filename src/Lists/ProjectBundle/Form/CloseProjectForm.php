<?php

namespace Lists\ProjectBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\Translator;
use Lists\ProjectBundle\Entity\ProjectStateTender;

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
                
                $project = $this->em->getRepository('ListsProjectBundle:Project')->find($projectId);

                if ($reasonClosed === null) {
                    $form->get('reasonClosed')->addError(
                        new FormError(
                            $translator->trans('The field can not be empty', array(), 'ITDoorsPayMasterBundle')
                        )
                    );
                }
                    
                if ($project instanceof ProjectStateTender) {
                    if (!$project) {
                        $form->addError(
                            new FormError(
                                $translator->trans('Project don`t found', array(), 'ListsProjectBundle')
                            )
                        );
                    }
                    $status = $project->getStatusProjectStateTender();
                    if ($status && $status->getAlias() == 'signing_document') {
                        $typeAcceptance = $this->em->getRepository('ListsProjectBundle:ProjectFileType')
                            ->findOneBy(array(
                                'alias' => 'acceptance'
                            ));
                        $fileAcceptance = $this->em->getRepository('ListsProjectBundle:ProjectFile')
                            ->findOneBy(array(
                                'type' => $typeAcceptance,
                                'project' => $project
                            ));
                        if (!$fileAcceptance || $fileAcceptance && (!$fileAcceptance->fileExists() || $fileAcceptance->getName() == '')) {
                            $form->addError(
                                new FormError(
                                    $translator->trans('Download please', array(), 'ListsProjectBundle')
                                    .': "'.$typeAcceptance->getName().'"'
                                )
                            );
                        }
                    }
                } elseif ($project->getStatus() && ($project->getStatus()->getAlias() == 'comm_proposal') && !$project->hasCommercialFile()) {
                    $typeComm = $this->em->getRepository('ListsProjectBundle:ProjectFileType')
                            ->findOneBy(array(
                                'alias' => 'commercial_offer'
                            ));
                    $form->addError(
                        new FormError(
                            $translator->trans('Download please', array(), 'ListsProjectBundle')
                            .': "'.$typeComm->getName().'"'
                        )
                    );
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
            'translation_domain' => 'ListsProjectBundle'
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
