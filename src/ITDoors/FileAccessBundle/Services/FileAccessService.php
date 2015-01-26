<?php

namespace ITDoors\FileAccessBundle\Services;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\File\File;
use ITDoors\FileAccessBundle\Entity\FileAccessRecord;
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
     * @param string  $path
     * @param integer $timestamp
     * 
     * @throws FileNotFoundException If the given path is not a file
     *
     * @return File
     */
    public function getFileIfAuthenticated($path, $timestamp)
    {
        // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->getFile($path, $timestamp);
        } else {
            return  null;
        }
    }

    /**
     * Returns file at given path if hasRole()
     *
     * @param string  $path
     * @param integer $timestamp
     * @param string  $role
     *
     * @throws FileNotFoundException If the given path is not a file
     *
     * @return File
     */
    public function getFileIfHasRole($path, $timestamp, $role)
    {
        if ($this->securityContext->isGranted($role)) {
            return $this->getFile($path, $timestamp);
        } else {
            return  null;
        }
    }

    private function getFile($path, $timestamp)
    {
        $fullPath = $this->projectWebDir . $path;
        $file = new File($fullPath);

        if ($file) {
            $date = new \DateTime();
            $date->setTimestamp($timestamp);
            $user = $this->securityContext->getToken()->getUser();

            $accessRecord = $this->em->getRepository('ITDoorsFileAccessBundle:FileAccessRecord')
                ->findOneBy(array(
                                'path' => $path,
                                'action' => 'get',
                                'date' => $date,
                                'user' => $user
                ));

            if (!$accessRecord) {
                $accessRecord = new FileAccessRecord(
                    $path,
                    'get',
                    $date,
                    $user
                );
                $this->em->persist($accessRecord);
                $this->em->flush();
            }
        }

        return $file;
    }
}
