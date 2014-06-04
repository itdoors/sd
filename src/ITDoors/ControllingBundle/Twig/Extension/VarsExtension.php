<?php

namespace ITDoors\ControllingBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;  
use \Twig_Extension;

/**
 * class VarsExtension
 */
class VarsExtension extends Twig_Extension
{
    protected $container;

    /**
     * __construct
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * 
     */
    public function __construct(ContainerInterface $container) 
    {
        $this->container = $container;
    }

    /**
     * getName
     * 
     * @return string
     */
    public function getName() 
    {
        return 'some.extension';
    }

    /**
     * getFilters
     * 
     * @return array
     */
    public function getFilters() {
        return array(
            'json_decode'   => new \Twig_Filter_Method($this, 'jsonDecode'),
        );
    }

    /**
     * jsonDecode
     * 
     * @param string $str
     * 
     * @return array
     */
    public function jsonDecode($str) {
        return json_decode($str);
    }
}