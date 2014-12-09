<?php

namespace Lists\CoachBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

/**
 * CoachService class
 */
class CoachService
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
}
