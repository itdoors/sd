<?php

namespace Lists\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;

/**
 * Class AddManagerForm
 */
class AddManagerForm extends AbstractType
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
            ->add('user', 'hidden_entity', array(
                'entity' => 'SDUserBundle:User',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $this->router->generate('sd_common_ajax_user'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    ))
                )
            ))
            ->add('part', 'text')
            ->add('project', 'hidden')
            ->add('cancel', 'submit')
            ->add('submit', 'submit');
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event)  {

                $data = $event->getData();

                $form = $event->getForm();

                $part = $data['part'];

//                $mainManager = $em
//                    ->getRepository('ListsHandlingBundle:HandlingUser')
//                    ->findOneBy(array(
//                        'handlingId' => $data['handlingId'],
//                        'lookupId' => $lookupId,
//                        ));
//
//                $isManager = $em
//                    ->getRepository('ListsHandlingBundle:HandlingUser')
//                    ->findOneBy(array(
//                        'handlingId' => $data['handlingId'],
//                        'userId' => $data['user'],
//                        ));
//                if (!is_int((int) $part)) {
//                    $msgString = "Mast be integer number";
//                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
//                    $form->addError(new FormError($msg));
//                } elseif ($part > 100) {
//                    $msgString = "Max. 100";
//                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
//                    $form->addError(new FormError($msg));
//                } elseif ($mainManager && $part > $mainManager->getPart()) {
//                    $msgString = "Max. ".$mainManager->getPart();
//                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
//                    $form->addError(new FormError($msg));
//                }
//                if ($isManager) {
//                    $msgString = "This user is already a manager";
//                    $msg = $translator->trans($msgString, array(), 'ListsHandlingBundle');
//                    $form->addError(new FormError($msg));
//                }
            }
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ProjectBundle\Entity\ManagerType',
            'validation_groups' => array('create'),
//            'csrf_protection' => false,
            'translation_domain' => 'ListsProjectBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'addManagerForm';
    }
}
