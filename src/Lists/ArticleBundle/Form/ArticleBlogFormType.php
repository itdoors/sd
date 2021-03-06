<?php
namespace Lists\ArticleBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ArticleHistoryFormType
 */
class ArticleBlogFormType extends AbstractType
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
        $builder->add('userId', 'text', array(
            'attr' => array(
                'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                'data-url' => $router->generate('sd_common_ajax_user_fio'),
                'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                'data-params' => json_encode(array(
                    'minimumInputLength' => 0,
                    'allowClear' => true
                )),
                'placeholder' => 'Enter fio'
            )
        ))
            ->add('datePublick', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('title', 'text', array())
            ->add('vote', 'checkbox', array(
                'required' => false,
                'mapped' => false
        ))
            ->add('manual', 'checkbox', array(
                'required' => false,
                'mapped' => false
        ))
            ->add('textShort', 'textarea', array(
                'required' => true
        ))
            ->add('companyList', 'hidden', array(
                'required' => false,
                'mapped' => false
        ))
//             ->add('roles', 'entity', array(
//             'class' => 'SD\UserBundle\Entity\Group',
//             'property' => 'name',
//             'empty_value' => '',
//             'mapped' => false,
//             'multiple' => true,
//             'required' => false
//         ))
            ->add('text', 'textarea', array())
//             ->add('file', 'file', array(
//                 'required' => false,
//                 'mapped' => false
//             ));
            ->add('files', 'collection', array(
                'required' => false,
                'type'=> new \Lists\ArticleBundle\Form\BlogFileForm(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'delete_empty'=> true
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver            
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\ArticleBundle\Entity\Article',
            'validation_groups' => array(
                'new'
            ),
            'translation_domain' => 'ListsArticleBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'articleBlogForm';
    }
}
