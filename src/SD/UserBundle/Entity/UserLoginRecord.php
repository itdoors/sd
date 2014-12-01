<?php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLoginRecord
 */
class UserLoginRecord
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $logedIn;

    /**
     * @var \DateTime
     */
    private $logedOut;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;


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
     * Set logedIn
     *
     * @param \DateTime $logedIn
     * 
     * @return UserLoginRecord
     */
    public function setLogedIn($logedIn)
    {
        $this->logedIn = $logedIn;

        return $this;
    }

    /**
     * Get logedIn
     *
     * @return \DateTime 
     */
    public function getLogedIn()
    {
        return $this->logedIn;
    }

    /**
     * Set logedOut
     *
     * @param \DateTime $logedOut
     * 
     * @return UserLoginRecord
     */
    public function setLogedOut($logedOut)
    {
        $this->logedOut = $logedOut;

        return $this;
    }

    /**
     * Get logedOut
     *
     * @return \DateTime 
     */
    public function getLogedOut()
    {
        return $this->logedOut;
    }

    /**
     * Set clientIp
     *
     * @param string $clientIp
     * 
     * @return UserLoginRecord
     */
    public function setClientIp($clientIp)
    {
        $this->clientIp = $clientIp;

        return $this;
    }

    /**
     * Get clientIp
     *
     * @return string 
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return UserLoginRecord
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

    /**
     * @var string
     */
    private $sessionId;


    /**
     * Set sessionId
     *
     * @param string $sessionId
     * 
     * @return UserLoginRecord
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }
}
