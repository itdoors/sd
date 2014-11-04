<?php

namespace Lists\DogovorBundle\Form;

use Lists\DogovorBundle\Entity\DopDogovor;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DopDogovorEditForm
 */
class DopDogovorEditForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;

        $builder
            ->add('dopDogovorType')
            ->add('number')
            ->add('startdatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('activedatetime', 'datetime', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('createDateTime', 'datetime', array(
                 'widget' => 'single_text',
            'format' => 'H:mm dd.MM.yyyy',
            'required' => false,
            'disabled' => true
            ))
            ->add('user', 'text', array(
                'data_class' => 'SD\UserBundle\Entity\User',
                'required' => false,
                'disabled' => true
            ))
            ->add('subject')
            ->add('isActive')
            ->add('total')
            ->add('dogovorId', 'hidden')
            ->add('saller', 'hidden_entity', array(
                'entity' => 'SDUserBundle:User'
            ));

        $builder
            ->add('add', 'submit')
            ->add('cancel', 'button');

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                /** @var DopDogovor $data */
                $data = $event->getData();

                $form = $event->getForm();

                $translator = $container->get('translator');

                if ($data->getStartdatetime() > $data->getActivedatetime()) {

                    $msgString = "Start date can't be greater then activate date";

                    $msg = $translator->trans($msgString, array(), 'ListsDogovorBundle');

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
            'data_class' => 'Lists\DogovorBundle\Entity\DopDogovor',
            'translation_domain' => 'ListsDogovorBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dopDogovorEditForm';
    }
}
