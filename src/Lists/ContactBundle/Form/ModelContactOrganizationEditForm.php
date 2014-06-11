<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormBuilderInterface;
use Lists\ContactBundle\Entity\ModelContact;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use SD\UserBundle\Entity\User;
use Symfony\Component\Form\FormError;

/**
 * Class ModelContactOrganizationEditForm
 */
class ModelContactOrganizationEditForm extends ModelContactOrganizationFormType
{
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $this->ur = $this->container->get('sd_user.repository');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $container = $this->container;

        /** @var User $user */
        $user = $container->get('security.context')->getToken()->getUser();

        $builder
            ->add('modelId', 'hidden', array(
                'required' => true
            ))
            ->add('phone1', null, array(
                'required' => false
            ));

        // Set default data for new contact
        $builder
            ->add('creatorDisabled', 'text', array(
                'disabled' => true,
                'mapped' => false,
            ))
            ->add('createdatetimeDisable', 'datetime', array(
                'mapped' => false,
                'disabled' => true,
                'widget' => 'single_text',
                'format' => 'dd.M.yyyy HH:mm',
            ));

        $data = $builder->getData();

        // Set owner depending on user role
        if ($user->hasRole('ROLE_SALESADMIN')) {
            $builder
                ->add('owner', 'hidden_entity', array(
                    'entity' => 'SDUserBundle:User',
                ));
        } else {
            if ($data) {
                if ($data->getOwner()) {
                    $builder
                        ->add('ownerDisable', 'text', array(
                            'disabled' => true,
                            'mapped' => false,
                            'data' => (string) $data->getOwner()
                        ));
                }
            }
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($user, $container) {
                $data = $event->getData();

                $form = $event->getForm();

                if ($data) {
                    if ($data->getOwnerId() == $user->getId() || $user->hasRole('ROLE_SALESADMIN')) {
                        $form->add('isShared');
                    }
                } else {
                    $form->add('isNew', 'hidden', array(
                        'mapped' => false
                    ));
                    $form->add('isShared');
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($user, $container) {
                $data = $event->getData();

                $form = $event->getForm();

                if ($data) {
                    if ($data->getId()) {
                        /** @var ModelContact $modelContact*/
                        $modelContact = $container->get('lists_contact.repository')->find($data->getId());

                        if ($modelContact) {
                            $creator = $modelContact->getUser();

                            $form->get('creatorDisabled')
                                ->setData((string) $creator);

                            $createDateTime = $modelContact->getCreatedatetime();

                            $form->get('createdatetimeDisable')
                                ->setData($createDateTime);
                        }
                    }
                } else {
                    $form->get('creatorDisabled')
                        ->setData((string) $user);

                    $createDateTime = new \DateTime();

                    $form->get('createdatetimeDisable')
                        ->setData($createDateTime);
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                if ($data) {
                    if (!$data->getModelId()) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Organization cant be empty", array(), 'ListsContactBundle');

                        $form->get('modelId')->addError(new FormError($msg));
                    }

                    if (!$data->getPhone1()) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Phone cant be empty", array(), 'ListsContactBundle');

                        $form->get('phone1')->addError(new FormError($msg));
                    }
                }
            }
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelContactOrganizationEditForm';
    }
}
