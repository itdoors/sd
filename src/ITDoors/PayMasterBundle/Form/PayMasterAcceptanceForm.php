<?php

namespace ITDoors\PayMasterBundle\Form;

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
 * PayMasterAcceptanceForm
 */
class PayMasterAcceptanceForm extends AbstractType
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
            ->add('isAcceptance', 'itdoors_choice', array(
                'required' => true,
                'empty_value' => '',
                'attr' => array(
                    'class' => 'form-control can-be-reseted',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0
                    )),
                    'placeholder' => 'Select is acceptance',
                ),
                'choices' => array(
                    '1' => 'Adopted',
                    '0' => 'Rejected'
                    )
                ))
            ->add('reason', 'textarea');

        $translator = $this->translator;
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($translator) {
                $form = $event->getForm();
                $isAcceptance = $form->get('isAcceptance')->getData();
                $reason = $form->get('reason')->getData();

                
                if ($isAcceptance === null ) {
                    $form->get('isAcceptance')->addError(
                        new FormError(
                            $translator->trans('The field can not be empty', array(), 'ITDoorsPayMasterBundle')
                        )
                    );
                } elseif ($isAcceptance === 0 && empty($reason)) {
                    $form->get('reason')->addError(
                        new FormError($translator->trans('The field can not be empty', array(), 'ITDoorsPayMasterBundle'))
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
            'translation_domain' => 'ITDoorsPayMasterBundle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'payMasterAcceptanceForm';
    }
}
