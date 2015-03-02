<?php

namespace Lists\MpkBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

/**
 * Class MpkForm
 */
class MpkForm extends AbstractType
{
    /**
     * @var Container $container
     */
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
        $translator = $this->container->get('translator');
        $em = $this->container->get('doctrine')->getManager();
        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $org */
        $org = $this->container->get('doctrine')->getManager()
            ->getRepository('Lists\OrganizationBundle\Entity\Organization');

        $builder
            ->add('name', 'text')
            ->add('startDate', 'text', array(
                'required' => false
            ))
            ->add('endDate', 'text', array(
                'required' => false
            ))
            ->add('active', 'choice', array(
                'attr' => array(
                    'class' => 'form-control input-middle',
                ),
                'choices' => array(
                    '' => $translator->trans('Select status', array(), 'ListsMpkBundle'),
                    '1' => $translator->trans('Active', array(), 'ListsMpkBundle'),
                    '0' => $translator->trans('Don`t active', array(), 'ListsMpkBundle')
                )
            ))
            ->add('organization', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\Organization',
                'property'=>'name',
                'empty_value' => '',
                'query_builder' => //$org->getOrganizationSignOwnQuery()
                            function(EntityRepository $er) {
                                return $er->createQueryBuilder('o')
                                    ->where('o.isSelf = :self')
                                    ->setParameter(':self', 'true')
                                    ->orderBy('o.name');
                            }
            ))
            ->add('department', 'entity', array(
                'class'=>'Lists\DepartmentBundle\Entity\Departments',
                'property'=>'name',
                'empty_value' => ''
            ))
            ->add('create', 'submit')
            ->add('cancel', 'submit');
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($translator, $em) {
                /** @var Mpk $data */
                $data = $event->getData();
                $form = $event->getForm();

                $mpk = $em->getRepository('ListsMpkBundle:Mpk')
                    ->findOneBy(array(
                        'name' => $data->getName(),
                        'organization' => $data->getOrganization()
                    ));
                if ($mpk) {
                    $msgString = "MPK already added";
                    $msg = $translator->trans($msgString, array(), 'ListsMpkBundle');
                    $msg .= ' | '.$mpk->getDepartment().' | '.$mpk->getOrganization();
                    $form->get('name')->addError(new FormError($msg));
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
            'data_class' => 'Lists\MpkBundle\Entity\Mpk',
            'translation_domain' => 'ListsMpkBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mpkForm';
    }
}
