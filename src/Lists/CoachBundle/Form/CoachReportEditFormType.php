<?php
namespace Lists\CoachBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CoachReportEditFormType
 */
class CoachReportEditFormType extends AbstractType
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
     * @param FormBuilderInterface $builder            
     * @param array                $options            
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router = $this->container->get('router');
        $builder
            ->add('action', new ActionEditFormType($this->container))
            ->add('created', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
        ))
            ->add('title', 'text', array())
            ->add('text', 'textarea', array(
                'required' => false
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver            
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\CoachBundle\Entity\CoachReport',
            'validation_groups' => array(
                'new'
            ),
            'translation_domain' => 'ListsCoachBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'coachReportEditForm';
    }
}
