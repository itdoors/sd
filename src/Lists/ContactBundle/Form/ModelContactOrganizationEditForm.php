<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use SD\UserBundle\Entity\User;



class ModelContactOrganizationEditForm extends ModelContactOrganizationFormType
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;

		/** @var \SD\UserBundle\Entity\UserRepository $ur */
		$this->ur = $this->container->get('sd_user.repository');
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder
			->add('modelId', 'hidden');

		/** @var User $user */
		$user = $this->container->get('security.context')->getToken()->getUser();

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function(FormEvent $event) use ($user)
			{
				$data = $event->getData();

				$form = $event->getForm();

				if ($data->getOwnerId() == $user->getId() || $user->hasRole('ROLE_SALESADMIN'))
				{
					$form->add('isShared');
				}
			});
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'modelContactOrganizationEditForm';
	}
}
