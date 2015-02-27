<?php

namespace ITDoors\FileAccessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *  "claimFile" = "ClaimFile",
 *  "claimMessageFile" = "ClaimMessageFile",
 *  "blogFile" = "BlogFile"})
 * @ORM\Entity
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \SD\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path = '/uploads/files/';

    /**
     * @var string
     *
     * @ORM\Column(name="origin_name", type="string", length=255)
     */
    protected $originName;

    /**
     * @var string
     *
     * @ORM\Column(name="real_name", type="string", length=255)
     */
    protected $realName;

    /**
<<<<<<< HEAD
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    protected $description;

    /**
=======
>>>>>>> master
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * 
     * @return File
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * Set originName
     *
     * @param string $originName
     * 
     * @return File
     */
    public function setOriginName($originName)
    {
        $this->originName = $originName;

        return $this;
    }

    /**
     * Get originName
     *
     * @return string 
     */
    public function getOriginName()
    {
        return $this->originName;
    }

    /**
     * Set realName
     *
     * @param string $realName
     * 
     * @return File
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;

        return $this;
    }

    /**
     * Get realName
     *
     * @return string 
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return File
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

    protected $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        $this->upload();
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * upload
     */
    protected function upload()
    {
        $file = $this->file;

        if (null === $file) {
            return;
        }

        $fileExtension = $file->getClientOriginalExtension();
        $this->setOriginName($file->getClientOriginalName());

        $filename = uniqid() . '.' . $fileExtension;

        $uploadDir = __DIR__ . '/../../../../web/' . $this->path;

        $file->move($uploadDir, $filename);

        $this->setRealName($filename);
        $this->setCreatedAt(new \DateTime());

        $this->file = null;
    }

    /**
     * Returns full link to file like '/uploads/...'
     * 
     * @return string
     */
    public function getLink()
    {
        if ($this->path !== null && $this->realName !== null) {
            return $this->path . $this->realName;
        } else {
            return '';
        }
    }

    /**
     * Set description
     *
     * @param string $description
     * 
     * @return File
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
