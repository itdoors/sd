<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class MessageCurrentForm
 */
class MessageCurrentForm extends AbstractType
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventDatetime', 'datetime', array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy HH:mm'
            ))
            ->add('type', 'entity', array(
                'class' => 'Lists\ProjectBundle\Entity\MessageType',
                'empty_value' => '',
                'required' => true,
            ))
            ->add('project', 'entity', array(
                'class' => 'Lists\ProjectBundle\Entity\Project',
                'required' => true,
            ))
            ->add('description')
            ->add('files', 'collection', array(
                'required' => false,
                'type'=> new FileMessageForm($this->em, $this->router, $this->translator),
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty'=> true
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ProjectBundle\Entity\MessageCurrent',
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsProjectBundle'
        ));
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'messageCurrentForm';
    }
}
