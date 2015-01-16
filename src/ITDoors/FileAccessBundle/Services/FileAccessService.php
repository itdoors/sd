<?php

namespace ITdoors\FileAccessBundle\Services;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\File\File;
use ITdoors\FileAccessBundle\Entity\FileAccessRecord;
use Doctrine\ORM\EntityManager;

/**
 * FileAccessService
 */
class FileAccessService
{
    protected $container;
    protected $projectWebDir;
    protected $em;
    protected $securityContext;

    /**
     * __construct
     *
     * @param Container         $container
     * @param string            $projectWebDir
     * @param EntityManager     $em
     * @param SecurityContext   $securityContext
     */
    public function __construct ($container, $projectWebDir, $em, $securityContext)
    {
        $this->em = $em;
        $this->container = $container;
        $this->projectWebDir = $projectWebDir;
        $this->securityContext = $securityContext;
    }

    /**
     * Returns file at given path if authenticated
     * 
     * @param string $path
     * 
     * @throws FileNotFoundException If the given path is not a file
     *
     * @return File
     */
    public function getFileIfAuthenticated($path)
    {
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->getFile($path);
        } else {
            return  null;
        }
    }

    /**
     * Returns file at given path if hasRole()
     *
     * @param string $path
     * @param string $role
     *
     * @throws FileNotFoundException If the given path is not a file
     *
     * @return File
     */
    public function getFileIfHasRole($path, $role)
    {
        if ($this->securityContext->isGranted($role)) {
            return $this->getFile($path);
        } else {
            return  null;
        }
    }

    private function getFile($path)
    {
        $fullPath = $this->projectWebDir . $path
        $file = new File($fullPath);

        if ($file) {
            $accessRecord = new FileAccessRecord(
                $path,
                'get',
                new \DateTime(),
                $this->securityContext->getToken()->getUser()
            );
            $this->em->persist($accessRecord);
            $this->em->flush;
        }

        return $file;
    }
}
