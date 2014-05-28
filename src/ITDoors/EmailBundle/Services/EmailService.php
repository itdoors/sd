<?php
namespace ITDoors\EmailBundle\Services;

use Symfony\Component\DependencyInjection\Container;

/**
 * Email Service class
 */
class EmailService
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
