<?php
namespace Lists\CompanystructureBundle\Form\Tree;
 
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\DoctrineType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * CompanystructureTreeType
 */
class CompanystructureTreeType extends DoctrineType
{
    /**
     * 
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
 
        $type = $this;
 
        $loader = function (Options $options) use ($type) {
            return $type->getLoader($options['em'], $options['query_builder'], $options['class']);
        };
 
        $resolver->setDefaults(array(
            'property'  => 'name',
            'loader'    => $loader,
            'class'     => 'ListsCompanystructureBundle:Companystructure',
            'attr'      => array('class' => 'input-block-level'),
        ));
    }

    /**
     * 
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param QueryBuilder                               $queryBuilder
     * @param object                                     $class
     * 
     * @return \Lists\CompanystructureBundle\Form\Tree\CompanystructureLoader
     */
    public function getLoader(ObjectManager $manager, $queryBuilder, $class)
    {
        return new CompanystructureLoader($manager, $queryBuilder, $class);
    }
 
    public function getName()
    {
        return 'companystructure_tree';
    }
}