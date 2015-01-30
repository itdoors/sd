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
 * GosTenderParticipationForm
 */
class GosTenderParticipationForm extends AbstractType
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
            ->add('participan', 'hidden_entity', array(
                'entity' => 'ListsOrganizationBundle:Organization',
                'data_class' => null,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $this->router->generate('lists_organization_ajax_search'),
                    'data-url-by-id' => $this->router->generate('lists_organization_ajax_organization_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'formatNoMatches'=> $this->translator->trans('Organization don`t found').
                            ' <a href="#" onclick="createOrganization()">'.$this->translator->trans('Create').'</a>'
                    ))
                )
            ))
            ->add('isWinner', 'itdoors_choice', array(
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
                    '1' => 'Yes',
                    '0' => 'No'
                    )
                ))
            ->add('gosTender', 'entity', array(
                'class' => 'ListsHandlingBundle:ProjectGosTender'
                )
            )
            ->add('summa', 'text')
            ->add('reason', 'textarea')
            ->add('cancel', 'submit')
            ->add('submit', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\HandlingBundle\Entity\ProjectGosTenderParticipan',
            'translation_domain' => 'ListsHandlingBundle',
            'validation_groups' => array('new')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gosTenderParticipationForm';
    }
}
