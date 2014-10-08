<?php

namespace SD\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Group
 */
class Group extends BaseGroup
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
