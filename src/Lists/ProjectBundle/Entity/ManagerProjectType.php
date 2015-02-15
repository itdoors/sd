<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ManagerProjectType
 */
class ManagerProjectType extends Manager
{
    /**
     * plusPart
     * 
     * @param integer $part
     */
    public function plusPart($part) {
        $partSumm = $this->getPart()+$part;
        $this->setPart($partSumm <= 100 ? $partSumm : 100);
    }
}