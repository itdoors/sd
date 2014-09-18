<?php

namespace SD\CalendarBundle\Services;

use Symfony\Component\DependencyInjection\Container;

/**
 * HolidayService class
 */
class HolidayService
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function newsletterHoliday($period)
    {
        
    }
}
