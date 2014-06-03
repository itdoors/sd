<?php

namespace ITDoors\EmailBundle\Entity;

use Symfony\Component\HttpFoundation\File\File as Sfile;
use Doctrine\ORM\Mapping as ORM;

/**
 * File
 */
class File
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $page;

    /**
     * @var string
     */
    private $tableId;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \DateTime
     */
    private $date;

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
     * Set userId
     *
     * @param integer $userId
     * 
     * @return File
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set page
     *
     * @param string $page
     * 
     * @return File
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return string 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set tableId
     *
     * @param string $tableId
     * 
     * @return File
     */
    public function setTableId($tableId)
    {
        $this->tableId = $tableId;

        return $this;
    }

    /**
     * Get tableId
     *
     * @return string 
     */
    public function getTableId()
    {
        return $this->tableId;
    }

    /**
     * Set tableName
     *
     * @param string $tableName
     * 
     * @return File
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * Get tableName
     *
     * @return string 
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Set name
     *
     * @param string $name
     * 
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * 
     * @return File
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
     * Set date
     *
     * @param \DateTime $date
     * 
     * @return File
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

    protected $file;

    /**
     * setFile
     * 
     * @param \Symfony\Component\HttpFoundation\File\File $file
     */
    public function setFile(Sfile $file = null)
    {
        $this->file = $file;
    }

    /**
     * getFile
     * 
     * @return file
     */
    public function getFile()
    {
        return $this->file;
    }
}
