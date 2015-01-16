<?php

namespace ITdoors\FileAccessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FileAccessRecord
 */
class FileAccessRecord
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $action;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * __construct
     *
     * @param string $path
     * @param string $action
     * @param string $date
     * @param User   $user
     */
    public function __construct ($path, $action, $date, $user)
    {
        $this->path = $path;
        $this->action = $action;
        $this->date = $date;
        $this->user = $user;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * 
     * @return FileAccessRecord
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set action
     *
     * @param string $action
     * 
     * @return FileAccessRecord
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * 
     * @return FileAccessRecord
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return FileAccessRecord
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
