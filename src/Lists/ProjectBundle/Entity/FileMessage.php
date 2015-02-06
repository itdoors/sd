<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FileMessage
 */
class FileMessage extends File
{

    /**
     * @var \Lists\ProjectBundle\Entity\Message
     */
    private $message;

    /**
     * Set message
     *
     * @param \Lists\ProjectBundle\Entity\Message $message
     *
     * @return FileMessage
     */
    public function setMessage (\Lists\ProjectBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }
    /**
     * Get message
     *
     * @return \Lists\ProjectBundle\Entity\Message 
     */
    public function getMessage ()
    {
        return $this->message;
    }
}