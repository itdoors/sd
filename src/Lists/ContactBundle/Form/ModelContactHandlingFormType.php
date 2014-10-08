<?php

namespace Lists\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\DependencyInjection\Container;
use Lists\ContactBundle\Entity\ModelContactLevelRepository;

/**
 * Class ModelContactHandlingFormType
 */
class ModelContactHandlingFormType extends AbstractType
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
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;

        $builder
            ->add('modelName', 'hidden', array(
                'data' => ModelContactRepository::MODEL_HANDLING
            ))
            ->add('modelId', 'hidden')
            ->add('firstName')
            ->add('lastName')
            ->add('middleName')
            ->add('phone1')
            ->add('phone2')
            ->add('position')
            ->add('birthday', 'birthday', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'years' => range(1900, date('Y')),
                'required' => false,
            ))
            ->add('type', null, array(
                'required' => true
            ))
            ->add('level', null, array(
                'required' => true,
                'empty_value' => '',
                'query_builder' => function (ModelContactLevelRepository $repository) {
                        return $repository->createQueryBuilder('mcl')
                            ->orderBy('mcl.digit', 'ASC');
                }
            ))
            ->add('email');

        $builder->add('add', 'submit');
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                if ($data) {
                    if (!$data->getPhone1() && !$data->getPhone2() && !$data->getEmail()) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Enter Phone or Phone mob or email", array(), 'ListsContactBundle');

                        $form->get('phone1')->addError(new FormError($msg));
                    }
                    if (!$data->getLevel()) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Enter level", array(), 'ListsContactBundle');

                        $form->get('level')->addError(new FormError($msg));
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
            'data_class' => 'Lists\ContactBundle\Entity\ModelContact',
            'translation_domain' => 'ListsContactBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelContactHandlingForm';
    }
}
