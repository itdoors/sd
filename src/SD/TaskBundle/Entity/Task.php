<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SD\TaskBundle\Interfaces\Serializable;

/**
 * Task
 */
class Task implements Serializable
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createDateTime;

    /**
     * @var \DateTime
     */
    private $startDateTime;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $author;

    /**
     * @var \SD\TaskBundle\Entity\Stage
     */
    private $stage;

    /**
     * @var \SD\TaskBundle\Entity\TaskType
     */
    private $type;

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
     * Set title
     *
     * @param string $title
     *
     * @return Task
     */
    public function setTitle ($title)
    {
        $this->title = $title;

        return $this;
    }
    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle ()
    {
        return $this->title;
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return Task
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
     * Set author
     *
     * @param \SD\UserBundle\Entity\User $author
     *
     * @return Task
     */
    public function setAuthor (\SD\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }
    /**
     * Get author
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getAuthor ()
    {
        return $this->author;
    }
    /**
     * Set stage
     *
     * @param \SD\TaskBundle\Entity\Stage $stage
     *
     * @return Task
     */
    public function setStage (\SD\TaskBundle\Entity\Stage $stage = null)
    {
        $this->stage = $stage;

        return $this;
    }
    /**
     * Get stage
     *
     * @return \SD\TaskBundle\Entity\Stage 
     */
    public function getStage ()
    {
        return $this->stage;
    }
    /**
     * Set type
     *
     * @param \SD\TaskBundle\Entity\TaskType $type
     *
     * @return Task
     */
    public function setType (\SD\TaskBundle\Entity\TaskType $type = null)
    {
        $this->type = $type;

        return $this;
    }
    /**
     * Get type
     *
     * @return \SD\TaskBundle\Entity\TaskType 
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $taskEndDates;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->taskEndDates = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Add taskEndDates
     *
     * @param \SD\TaskBundle\Entity\TaskEndDate $taskEndDates
     *
     * @return Task
     */
    public function addTaskEndDate (\SD\TaskBundle\Entity\TaskEndDate $taskEndDates)
    {
        $this->taskEndDates[] = $taskEndDates;

        return $this;
    }
    /**
     * Remove taskEndDates
     *
     * @param \SD\TaskBundle\Entity\TaskEndDate $taskEndDates
     */
    public function removeTaskEndDate (\SD\TaskBundle\Entity\TaskEndDate $taskEndDates)
    {
        $this->taskEndDates->removeElement($taskEndDates);
    }
    /**
     * Get taskEndDates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTaskEndDates ()
    {
        return $this->taskEndDates;
    }

    /**
     * @var \DateTime
     */
    private $createDate;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return Task
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Task
     */
    public function setStartDate ($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }
    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate ()
    {
        return $this->startDate;
    }

    /**
     * sleep method
     *
     * @return array
     */
    public function customSerialize()
    {
        $return = array(
            'id'=>$this->getId(),
            'createDateTime' => $this->getCreateDate(),
            'startDateTime' => $this->getStartDate(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'author' => $this->getAuthor()->customSerialize(),
            'stage' => $this->getStage()->customSerialize()
        );
        $return['taskEndDates'] = array();
        foreach ($this->getTaskEndDates() as $taskEndDate) {
            $return['taskEndDates'][] = $taskEndDate->customSerialize();
        }
            //'type' => $this->getType()->customSerialize()
        return $return;
    }
}
