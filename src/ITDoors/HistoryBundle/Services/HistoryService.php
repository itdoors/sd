<?php

namespace ITDoors\HistoryBundle\Services;

use Symfony\Component\DependencyInjection\Container;

/**
 * HistoryService class
 */
class HistoryService
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
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}