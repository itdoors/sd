<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ProjectFile
 */
class ProjectFile
{
    /**
     * @var string
     */
    private $path = 'uploads/projects/';
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $shortText;

    /**
     * @var \DateTime
     */
    private $createDatetime;

    /**
     * @var \DateTime
     */
    private $deletedDatetime;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $project;

    /**
     * Get path
     *
     * @return string 
     */
    private function getPath ()
    {
        return $this->path;
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
     * Set name
     *
     * @param string $name
     * @return ProjectFile
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
     * Set shortText
     *
     * @param string $shortText
     * @return ProjectFile
     */
    public function setShortText($shortText)
    {
        $this->shortText = $shortText;

        return $this;
    }

    /**
     * Get shortText
     *
     * @return string 
     */
    public function getShortText()
    {
        return $this->shortText;
    }

    /**
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     * @return ProjectFile
     */
    public function setCreateDatetime($createDatetime)
    {
        $this->createDatetime = $createDatetime;

        return $this;
    }

    /**
     * Get createDatetime
     *
     * @return \DateTime 
     */
    public function getCreateDatetime()
    {
        return $this->createDatetime;
    }

    /**
     * Set deletedDatetime
     *
     * @param \DateTime $deletedDatetime
     * @return ProjectFile
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

    /**
     * Set project
     *
     * @param \Lists\HandlingBundle\Entity\Handling $project
     * @return ProjectFile
     */
    public function setProject(\Lists\HandlingBundle\Entity\Handling $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Lists\HandlingBundle\Entity\Handling 
     */
    public function getProject()
    {
        return $this->project;
    }
    /**
     * getAbsolutePath
     *
     * @return null|string
     */
    public function getAbsolutePath ()
    {
        return null === $this->getName() ? null : $this->getUploadRootDir() . '/' . $this->getName();
    }
    /**
     * getWebPath
     *
     * @return null|string
     */
    public function getWebPath ()
    {
        return null === $this->getName() ? null : $this->getUploadDir() . '/' . $this->getName();
    }
    /**
     * getUploadRootDir
     *
     * @return string
     */
    protected function getUploadRootDir ()
    {
        $dir = __DIR__ . '/../../../../web/' . $this->getUploadDir();

        return $dir;
    }
    /**
     * getUploadDir
     *
     * @return string
     */
    protected function getUploadDir ()
    {
        return $this->getPath().$this->getProject()->getId().'/';
    }

    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile (UploadedFile $file = null)
    {
        $this->file = $file;
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile ()
    {
        return $this->file;
    }
    /**
     * upload
     */
    public function upload ()
    {
        if (null === $this->getFile()) {
            return;
        }

        $fileExtension = $this->getFile()->getClientOriginalExtension();

        $filename = uniqid() . '.' . $fileExtension;

        $uploadDir = $this->getUploadRootDir();

        $this->getFile()->move($uploadDir, $filename);

        $this->setName($filename);

        $this->file = null;
    }
}
