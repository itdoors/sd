<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 */
class Project
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var float
     */
    private $square;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $isClosed;

    /**
     * @var \DateTime
     */
    private $datetimeClosed;

    /**
     * @var string
     */
    private $reasonClosed;

    /**
     * @var \DateTime
     */
    private $statusChangeDate;

    /**
     * @var \DateTime
     */
    private $createDate;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $messages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $managers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $files;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $userCreated;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $userClosed;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->createDatetime = new \DateTime();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->managers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     * @return Project
     */
    public function setCreateDatetime ($createDatetime)
    {
        $this->createDatetime = $createDatetime;

        return $this;
    }
    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime ()
    {
        return $this->createDatetime;
    }
    /**
     * Set square
     *
     * @param float $square
     *
     * @return Project
     */
    public function setSquare ($square)
    {
        $this->square = $square;

        return $this;
    }
    /**
     * Get square
     *
     * @return float 
     */
    public function getSquare ()
    {
        return $this->square;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription ($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription ()
    {
        return $this->description;
    }
    /**
     * Set isClosed
     *
     * @param boolean $isClosed
     *
     * @return Project
     */
    public function setIsClosed ($isClosed)
    {
        $this->isClosed = $isClosed;

        return $this;
    }
    /**
     * Get isClosed
     *
     * @return boolean 
     */
    public function getIsClosed ()
    {
        return $this->isClosed;
    }
    /**
     * Set datetimeClosed
     *
     * @param \DateTime $datetimeClosed
     *
     * @return Project
     */
    public function setDatetimeClosed ($datetimeClosed)
    {
        $this->datetimeClosed = $datetimeClosed;

        return $this;
    }
    /**
     * Get datetimeClosed
     *
     * @return \DateTime 
     */
    public function getDatetimeClosed ()
    {
        return $this->datetimeClosed;
    }
    /**
     * Set reasonClosed
     *
     * @param string $reasonClosed
     *
     * @return Project
     */
    public function setReasonClosed ($reasonClosed)
    {
        $this->reasonClosed = $reasonClosed;

        return $this;
    }
    /**
     * Get reasonClosed
     *
     * @return string 
     */
    public function getReasonClosed ()
    {
        return $this->reasonClosed;
    }
    /**
     * Set statusChangeDate
     *
     * @param \DateTime $statusChangeDate
     *
     * @return Project
     */
    public function setStatusChangeDate ($statusChangeDate)
    {
        $this->statusChangeDate = $statusChangeDate;

        return $this;
    }
    /**
     * Get statusChangeDate
     *
     * @return \DateTime 
     */
    public function getStatusChangeDate ()
    {
        return $this->statusChangeDate;
    }
    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Project
     */
    public function setCreateDate ($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }
    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate ()
    {
        return $this->createDate;
    }
    /**
     * Add messages
     *
     * @param \Lists\ProjectBundle\Entity\Message $messages
     *
     * @return Project
     */
    public function addMessage (\Lists\ProjectBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }
    /**
     * Remove messages
     *
     * @param \Lists\ProjectBundle\Entity\Message $messages
     */
    public function removeMessage (\Lists\ProjectBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }
    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages ()
    {
        return $this->messages;
    }
    /**
     * Add managers
     *
     * @param \Lists\ProjectBundle\Entity\Manager $managers
     *
     * @return Project
     */
    public function addManager (\Lists\ProjectBundle\Entity\Manager $managers)
    {
        $this->managers[] = $managers;

        return $this;
    }
    /**
     * Remove managers
     *
     * @param \Lists\ProjectBundle\Entity\Manager $managers
     */
    public function removeManager (\Lists\ProjectBundle\Entity\Manager $managers)
    {
        $this->managers->removeElement($managers);
    }
    /**
     * Get managers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getManagers ()
    {
        return $this->managers;
    }
    /**
     * Add files
     *
     * @param \Lists\ProjectBundle\Entity\File $files
     *
     * @return Project
     */
    public function addFile (\Lists\ProjectBundle\Entity\File $files)
    {
        $this->files[] = $files;

        return $this;
    }
    /**
     * Remove files
     *
     * @param \Lists\ProjectBundle\Entity\File $files
     */
    public function removeFile (\Lists\ProjectBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }
    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles ()
    {
        return $this->files;
    }
    /**
     * Set userCreated
     *
     * @param \SD\UserBundle\Entity\User $userCreated
     *
     * @return Project
     */
    public function setUserCreated (\SD\UserBundle\Entity\User $userCreated = null)
    {
        $this->userCreated = $userCreated;

        return $this;
    }
    /**
     * Get userCreated
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUserCreated ()
    {
        return $this->userCreated;
    }
    /**
     * Set userClosed
     *
     * @param \SD\UserBundle\Entity\User $userClosed
     *
     * @return Project
     */
    public function setUserClosed (\SD\UserBundle\Entity\User $userClosed = null)
    {
        if ($userClosed) {
            $this->setDatetimeClosed(new \DateTime());
            $this->setIsClosed(true);
        }
        $this->userClosed = $userClosed;

        return $this;
    }
    /**
     * Get userClosed
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUserClosed ()
    {
        return $this->userClosed;
    }
    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     *
     * @return Project
     */
    public function setOrganization (\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }
    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization ()
    {
        return $this->organization;
    }
    /**
     * @var \DateTime
     */
    private $deletedDatetime;


    /**
     * Set deletedDatetime
     *
     * @param \DateTime $deletedDatetime
     * @return Project
     */
    public function setDeletedDatetime($deletedDatetime)
    {
        $this->deletedDatetime = $deletedDatetime;
    
        return $this;
    }

    /**
     * Get deletedDatetime
     *
     * @return \DateTime 
     */
    public function getDeletedDatetime()
    {
        return $this->deletedDatetime;
    }
}