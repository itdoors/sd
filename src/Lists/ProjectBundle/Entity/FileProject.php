<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FileProject
 */
class FileProject extends File
{

    /**
     * @var \Lists\ProjectBundle\Entity\Project
     */
    private $project;

    /**
     * @var \Lists\ProjectBundle\Entity\ProjectFileType
     */
    private $type;

    /**
     * Set project
     *
     * @param \Lists\ProjectBundle\Entity\Project $project
     *
     * @return FileProject
     */
    public function setProject (\Lists\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }
    /**
     * Get project
     *
     * @return \Lists\ProjectBundle\Entity\Project 
     */
    public function getProject ()
    {
        return $this->project;
    }
    /**
     * Set type
     *
     * @param \Lists\ProjectBundle\Entity\ProjectFileType $type
     *
     * @return FileProject
     */
    public function setType (\Lists\ProjectBundle\Entity\ProjectFileType $type = null)
    {
        $this->type = $type;

        return $this;
    }
    /**
     * Get type
     *
     * @return \Lists\ProjectBundle\Entity\ProjectFileType 
     */
    public function getType ()
    {
        return $this->type;
    }
}