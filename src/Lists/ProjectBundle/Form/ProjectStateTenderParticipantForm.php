<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
Use Symfony\Component\Translation\Translator;

/**
 * Class ProjectStateTenderParticipantForm
 */
class ProjectStateTenderParticipantForm extends AbstractType
{
    protected $em;
    protected $router;
    protected $translator;

    /**
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('participant', 'hidden_entity', array(
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
                    'placeholder' => 'Select status',
                ),
                'choices' => array(
                    '1' => 'Yes',
                    '0' => 'No'
                    )
                ))
            ->add('projectStateTender', 'entity', array(
                'class' => 'ListsProjectBundle:ProjectStateTender'
                )
            )
            ->add('summa', 'text')
            ->add('reason', 'textarea')
            ->add('cancel', 'submit')
            ->add('submit', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ProjectBundle\Entity\ProjectStateTenderParticipant',
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsProjectBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'projectStateTenderParticipantForm';
    }
}
