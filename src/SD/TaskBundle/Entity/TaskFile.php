<?php

namespace SD\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * TaskFile
 */
class TaskFile
{

    /**
     * getAbsolutePath
     *
     * @return null|string
     */
    public function getAbsolutePath ()
    {
        return null === $this->filepath ? null : $this->getUploadRootDir() . '/' . $this->filepath;
    }
    /**
     * getWebPath
     *
     * @return null|string
     */
    public function getWebPath ()
    {
        return null === $this->filepath ? null : $this->getUploadDir() . '/' . $this->filepath;
    }
    /**
     * getUploadRootDir
     *
     * @return string
     */
    protected function getUploadRootDir ()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }
    /**
     * getUploadDir
     *
     * @return string
     */
    protected function getUploadDir ()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/task';
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
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to

        $fileExtension = $this->getFile()->getClientOriginalExtension();

        $newFileName = md5(microtime());

        $filepath = $newFileName . '.' . $fileExtension;

        $uploadDir = $this->getUploadRootDir();
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777);
        }

        $this->name = $this->getFile()->getClientOriginalName();
        $this->getFile()->move(
            $uploadDir,
            $filepath
        );

        // set the path property to the filename where you've saved the file
        $this->filepath = $filepath;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createDate;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \SD\TaskBundle\Entity\Task
     */
    private $task;


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
     * Set createDate
     *
     * @param \DateTime $createDate
     *
     * @return TaskFile
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TaskFile
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
     * Set filepath
     *
     * @param string $filepath
     *
     * @return TaskFile
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return TaskFile
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
     * Set task
     *
     * @param \SD\TaskBundle\Entity\Task $task
     *
     * @return TaskFile
     */
    public function setTask(\SD\TaskBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \SD\TaskBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }
}