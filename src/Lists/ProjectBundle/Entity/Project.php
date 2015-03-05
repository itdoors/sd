<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lists\ProjectBundle\Entity\ManagerProjectType;

/**
 * Project
 */
class Project
{
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
     * __toString
     * 
     * @return string
     */
    public function __toString ()
    {
        return (string) $this->getId();
    }

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
     * isManager
     *
     * @return boolean
     */
    public function isManager (\SD\UserBundle\Entity\User $user)
    {
        $managers = $this->getManagers();
        foreach ($managers as $manager) {
            if ($manager->getUser() == $user) {
                return true;
            }
        }

        return false;
    }
    /**
     * isManager
     *
     * @return boolean
     */
    public function getMaxPart ()
    {
        $part = 0;
        $managers = $this->getManagers();
        foreach ($managers as $manager) {
            $part =+ $manager->getPart();
        }

        return $part <= 100 ? 100-$part : 0;
    }
    /**
     * isManager
     *
     * @return boolean
     */
    public function isManagerProject (\SD\UserBundle\Entity\User $user)
    {
        $managers = $this->getManagers();
        foreach ($managers as $manager) {
            if ($manager->getUser() == $user and $manager instanceof ManagerProjectType) {
                return true;
            }
        }

        return false;
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
    public function setDeletedDatetime ($deletedDatetime)
    {
        $this->deletedDatetime = $deletedDatetime;

        return $this;
    }
    /**
     * Get deletedDatetime
     *
     * @return \DateTime 
     */
    public function getDeletedDatetime ()
    {
        return $this->deletedDatetime;
    }

    /**
     * @var boolean
     */
    private $statusAccess;

    /**
     * Set statusAccess
     *
     * @param boolean $statusAccess
     * @return Project
     */
    public function setStatusAccess ($statusAccess)
    {
        $this->statusAccess = $statusAccess;

        return $this;
    }
    /**
     * Get statusAccess
     *
     * @return boolean 
     */
    public function getStatusAccess ()
    {
        return $this->statusAccess;
    }
    /**
     * getCoiceConfirm
     * 
     * @return mixed[]
     */
    public static function getCoiceConfirm ()
    {
        return array (
            true => 'Yes',
            false => 'No'
        );
    }

    /**
     * @var \Lists\ProjectBundle\Entity\Status
     */
    private $status;

    /**
     * Set status
     *
     * @param \Lists\ProjectBundle\Entity\Status $status
     * @return Project
     */
    public function setStatus (\Lists\ProjectBundle\Entity\Status $status = null)
    {
        $this->setStatusChangeDate(new \DateTime());
        $this->status = $status;

        return $this;
    }
    /**
     * Get status
     *
     * @return \Lists\ProjectBundle\Entity\Status 
     */
    public function getStatus ()
    {
        return $this->status;
    }

    /**
     * @var string
     */
    private $statusText;

    /**
     * Set statusText
     *
     * @param string $statusText
     * @return Project
     */
    public function setStatusText ($statusText)
    {
        $this->statusText = $statusText;

        return $this;
    }
    /**
     * Get statusText
     *
     * @return string 
     */
    public function getStatusText ()
    {
        return $this->statusText;
    }
    /**
     * hasCommercialFile
     *
     * @return string 
     */
    public function hasCommercialFile ()
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            if ($file->getType() && $file->getType()->getAlias() == 'commercial_offer' && $file->getName() != '') {
                return true;
            }
        }
        return false;
    }

    /**
     * @var \Lists\ArticleBundle\Entity\Article
     */
    private $notification;

    /**
     * Set notification
     *
     * @param \Lists\ArticleBundle\Entity\Article $notification
     *
     * @return Project
     */
    public function setNotification (\Lists\ArticleBundle\Entity\Article $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }
    /**
     * Get notification
     *
     * @return \Lists\ArticleBundle\Entity\Article 
     */
    public function getNotification ()
    {
        return $this->notification;
    }

    /**
     * @var \Lists\ProjectBundle\Entity\MessageCurrent
     */
    private $lastMessageCurrent;

    /**
     * @var \Lists\ProjectBundle\Entity\MessagePlanned
     */
    private $lastMessagePlanned;

    /**
     * Set lastMessageCurrent
     *
     * @param \Lists\ProjectBundle\Entity\MessageCurrent $lastMessageCurrent
     *
     * @return Project
     */
    public function setLastMessageCurrent (\Lists\ProjectBundle\Entity\MessageCurrent $lastMessageCurrent = null)
    {
        $this->lastMessageCurrent = $lastMessageCurrent;

        return $this;
    }
    /**
     * Get lastMessageCurrent
     *
     * @return \Lists\ProjectBundle\Entity\MessageCurrent 
     */
    public function getLastMessageCurrent ()
    {
        return $this->lastMessageCurrent;
    }
    /**
     * Set lastMessagePlanned
     *
     * @param \Lists\ProjectBundle\Entity\MessagePlanned $lastMessagePlanned
     *
     * @return Project
     */
    public function setLastMessagePlanned (\Lists\ProjectBundle\Entity\MessagePlanned $lastMessagePlanned = null)
    {
        $this->lastMessagePlanned = $lastMessagePlanned;

        return $this;
    }
    /**
     * Get lastMessagePlanned
     *
     * @return \Lists\ProjectBundle\Entity\MessagePlanned 
     */
    public function getLastMessagePlanned ()
    {
        return $this->lastMessagePlanned;
    }

    /**
     * @var float
     */
    private $pf;

    /**
     * Set pf
     *
     * @param float $pf
     *
     * @return Project
     */
    public function setPf ($pf)
    {
        $this->pf = $pf;

        return $this;
    }
    /**
     * Get pf
     *
     * @return float 
     */
    public function getPf ()
    {
        return $this->pf;
    }

    /**
     * @var float
     */
    private $summaWithVAT;

    /**
     * Set summaWithVAT
     *
     * @param float $summaWithVAT
     *
     * @return Project
     */
    public function setSummaWithVAT ($summaWithVAT)
    {
        $this->summaWithVAT = $summaWithVAT;

        return $this;
    }
    /**
     * Get summaWithVAT
     *
     * @return float 
     */
    public function getSummaWithVAT ()
    {
        return $this->summaWithVAT;
    }
    /**
     * Get Manager Project
     *
     * @return float 
     */
    public function getManagerProject ()
    {
        $managers = $this->getManagers();
        foreach ($managers as $manager) {
            if ($manager instanceof ManagerProjectType) {
                return $manager;
            }
        }
        
        return null;
    }
    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;


    /**
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     * @return Project
     */
    public function setDogovor(\Lists\DogovorBundle\Entity\Dogovor $dogovor = null)
    {
        $this->dogovor = $dogovor;
    
        return $this;
    }

    /**
     * Get dogovor
     *
     * @return \Lists\DogovorBundle\Entity\Dogovor 
     */
    public function getDogovor()
    {
        return $this->dogovor;
    }

    /**
     * getСontractЕime
     *
     * @return \DateTime
     */
    public function getСontractЕime()
    {
        $dogovor = $this->getDogovor();
        if (!$dogovor) {
            return null;
        }

        return $dogovor->getStopdatetime();
    }
}