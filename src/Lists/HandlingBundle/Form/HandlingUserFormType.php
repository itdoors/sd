<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

/**
 * Class HandlingUserFormType
 */
class HandlingUserFormType extends AbstractType
{
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
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;

        $builder
            ->add('user', 'text')
            ->add('part', 'text')
            ->add('handlingId', 'hidden')
            ->add('cancel', 'submit')
            ->add('add', 'submit');
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                $em = $this->container->get('doctrine')->getManager();
                $translator = $container->get('translator');

                $data = $event->getData();

                $form = $event->getForm();

                $part = $data['part'];

                $lookupId = $em
                    ->getRepository('ListsLookupBundle:Lookup')->getOnlyManagerProjectId();

                $mainManager = $em
                    ->getRepository('ListsHandlingBundle:HandlingUser')
                    ->findOneBy(array(
                        'handlingId' => $data['handlingId'],
                        'lookupId' => $lookupId,
                        ));

                $isManager = $em
                    ->getRepository('ListsHandlingBundle:HandlingUser')
                    ->findOneBy(array(
                        'handlingId' => $data['handlingId'],
                        'userId' => $data['user'],
                        ));
                if (!is_int((int) $part)) {
                    $msgString = "Mast be integer number";
                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
                    $form->addError(new FormError($msg));
                } elseif ($part > 100) {
                    $msgString = "Max. 100";
                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
                    $form->addError(new FormError($msg));
                } elseif ($mainManager && $part > $mainManager->getPart()) {
                    $msgString = "Max. ".$mainManager->getPart();
                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
                    $form->addError(new FormError($msg));
                }
                if ($isManager) {
                    $msgString = "This user is already a manager";
                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
                    $form->addError(new FormError($msg));
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
            'data_class' => null,
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingUserForm';
    }
}
