<?php

namespace Lists\CompanystructureBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Lists\CompanystructureBundle\Entity\Companystructure;

/**
 * Companystructure Service class
 */
class CompanystructureService
{

    /**
     * @var Container $container
     */
    protected $container;
    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct (Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function constructTree ()
    {
        $em = $this->container->get('doctrine')->getManager();
        $companystructure = $em
            ->getRepository('ListsCompanystructureBundle:Companystructure');
        $companystructure->verify();
        $companystructure->recover();
        $em->flush();
    }
}
