<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

/**
 * Class ModelContactOrganizationWizardForm
 */
class ModelContactOrganizationWizardForm extends ModelContactOrganizationFormType
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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $container = $this->container;
        
        $session = $container->get('session');

        $organization = $session->get('sales.wizard.organization');
        
        $organizationstr = isset($organization['organizationName']) ? $organization['organizationName'] : null;
            
        $builder
            ->add('organization', 'text', array(
                'disabled' => true,
                'data' => (string) $organizationstr
        ));

        $builder
            ->remove('modelId')
            ->remove('cancel');


        $builder->addEventListener(
            FormEvents::POST_SUBMIT, function (FormEvent $event) use ($container) {
            $data = $event->getData();

            $form = $event->getForm();

            if ($data) {
                if (!$data->getPhone1() && !$data->getPhone2() && !$data->getEmail()) {
                    $translator = $container->get('translator');

                    $msg = $translator->trans("Enter Phone or Phone mob or email", array(), 'ListsContactBundle');

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
        return 'modelContactOrganizationWizardForm';
    }
}
