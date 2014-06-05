<?php

namespace Lists\ContactBundle\Services;

use Lists\ContactBundle\Entity\ModelContactLevel;
use Lists\ContactBundle\Entity\ModelContactLevelRepository;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ContactService
 */
class ModelContactService
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
     * Get all contact levels order by digit
     *
     * @return ModelContactLevel[]
     */
    public function getLevels()
    {
        /** @var ModelContactLevelRepository $mclr */
        $mclr = $this->container->get('lists_contact.level.repository');

        $levels = $mclr->getLevels();

        return $levels;
    }
}
