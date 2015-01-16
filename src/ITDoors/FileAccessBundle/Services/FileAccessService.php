<?php

namespace ITdoors\FileAccessBundle\Services;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\File;

/**
 * FileAccessService
 */
class FileAccessService
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * @var string $projectWebDir
     */
    protected $projectWebDir;

    /**
     * __construct
     *
     * @param Container $container
     * @param string    $projectWebDir
     */
    public function __construct (Container $container, $projectWebDir)
    {
        $this->container = $container;
        $this->projectWebDir = $projectWebDir;
    }

    /**
     * Returns file at given path
     * 
     * @param string $path
     * 
     * @throws FileNotFoundException If the given path is not a file
     *
     * @return File
     */
    public function getFileIfAuthenticated($path)
    {
        $securityContext = $this->container->get('security.context');

        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $fullPath = $this->projectWebDir . $path
            $file = new File($fullPath);

            return $file;
        } else {
            return  null;
        }
    }
}
