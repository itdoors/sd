<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
Use Symfony\Component\Translation\Translator;

/**
 * Class AddDocumentForm
 */
class AddDocumentForm extends AbstractType
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
//             ->add('type', 'hidden_entity', array(
//                'class' => 'ListsHandlingBundle:ProjectFileType',
//                'empty_value' => ''
//            ))
            ->add('project', 'entity', array(
                'class' => 'ListsHandlingBundle:Handling'
                )
            )
            ->add('name', 'file')
            ->add('shortText', 'textarea')
            ->add('cancel', 'submit')
            ->add('submit', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\HandlingBundle\Entity\ProjectFile',
            'validation_groups' => array('add'),
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'addDocumentForm';
    }
}
