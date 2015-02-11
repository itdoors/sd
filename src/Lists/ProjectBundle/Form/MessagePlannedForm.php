<?php

namespace Lists\ProjectBundle\Form;


use Symfony\Component\Form\FormBuilderInterface;
use Lists\ProjectBundle\Form\MessageCurrentForm;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class MessagePlannedForm
 */
class MessagePlannedForm extends MessageCurrentForm
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('eventDatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy HH:mm'
            ));
//        $builder
//            ->add('createdate', 'datetime', array(
//                'data' => new \DateTime(),
//                'widget' => 'single_text',
//                'format' => 'dd.M.yyyy HH:mm'
//            ))
//            ->add('type', null, array(
//                'empty_value' => '',
//                'required' => true,
//            ))
//            ->add('nextcreatedate', 'datetime', array(
//                'required' => true,
//                'mapped' => false,
//                'widget' => 'single_text',
//                'format' => 'dd.M.yyyy HH:mm'
//            ))
//            ->add('nexttype', 'entity', array(
//                'class' => 'ListsHandlingBundle:HandlingMessageType',
//                'required' => true,
//                'empty_value' => '',
//                'mapped' => false
//            ))
//            ->add('next_is_business_trip', 'checkbox', array(
//                'mapped' => false,
//                'required' => false
//            ))
//            ->add('description')
//            ->add('descriptionnext', 'text', array(
//                'required' => false,
//                'mapped' => false
//            ))
//            ->add('filename')
//            ->add('files', 'file', array(
//                'required' => false,
//                'multiple' => true,
//                'mapped' => false
//            ))
//            ->add('handling_id', 'hidden')
//            ->add('mindate', 'hidden', array(
//                'mapped' => false
//            ));

//        if ($this->user->hasRole('ROLE_SALESADMIN')) {
//            $builder
//                ->add('user', 'hidden_entity', array(
//                    'entity' => 'SDUserBundle:User',
//                    'data_class' => null,
//                    'data' => $this->user
//                ))
//                ->add('userNext', 'hidden_entity', array(
//                    'entity' => 'SDUserBundle:User',
//                    'data_class' => null,
//                    'data' => $this->user,
//                    'mapped' => false
//                ));
//        }

//        $builder
//            ->add('submit', 'submit')
//            ->add('cancel', 'submit');

//        $translator = $this->translator;
//        $builder->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function (FormEvent $event) use ($translator) {
//                $data = $event->getData();
//
//                $form = $event->getForm();
//
//                $currentDatetime = new \DateTime($data['createdate']);
//                $nextDatetime = new \DateTime($data['nextcreatedate']);
//                if ($currentDatetime > $nextDatetime) {
//
//                    $msgString = "Event next date can't be greater then current event date";
//
//                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
//
//                    $form->addError(new FormError($msg));
//                }
//            }
//        );
//
//        $builder->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function (FormEvent $event) use ($translator) {
//                $data = $event->getData();
//
//                $form = $event->getForm();
//
//                if (!$data['nextcreatedate']) {
//
//                    $msg = $translator->trans("Event next date can't be empty", array(), 'ListsHandlingBundle');
//
//                    $form->addError(new FormError($msg));
//                }
//            }
//        );
//
//        $builder->addEventListener(
//            FormEvents::PRE_SUBMIT,
//            function (FormEvent $event) use ($translator) {
//                $data = $event->getData();
//
//                $form = $event->getForm();
//
//                $currentDatetime = new \DateTime($data['createdate']);
//
//                if (isset($data['handling_id']) && $data['handling_id']) {
//                    $handlingId = $data['handling_id'];
//
//                    /** @var \Lists\HandlingBundle\Entity\Handling $handling */
////                    $handling = $container->get('doctrine.orm.entity_manager')
////                        ->getRepository('ListsHandlingBundle:Handling')
////                        ->find($handlingId);
////
////                    if ($handling) {
////                        if ($handling->getCreatedate() > $currentDatetime) {
////                            $translator = $container->get('translator');
////
////                            $creationDate = $handling->getCreatedate()  ?
////                                $handling->getCreatedate() :
////                                $handling->getCreatedatetime();
////
////                            $msgString = "Current event date can't be less then handling creation date (%date%)";
////
////                            $msg = $translator->trans($msgString, array(
////                                '%date%' => $creationDate->format('d.m.y')
////                            ), 'ListsHandlingBundle');
////
////                            $form->addError(new FormError($msg));
////                        }
////                    }
//                }
//            }
//        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ProjectBundle\Entity\MessagePlanned',
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsProjectBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'addMessageForm';
    }
}
