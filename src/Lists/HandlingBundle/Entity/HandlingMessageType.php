<?php

namespace Lists\HandlingBundle\Entity;

/**
 * HandlingMessageType
 */
class HandlingMessageType
{
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
    private $slug;

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
     *
     * @return HandlingMessageType
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
     * Set slug
     *
     * @param string $slug
     *
     * @return HandlingMessageType
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @var integer
     */
    private $stayActionTime;

    /**
     * @var integer
     */
    private $sortorder;

    /**
     * Set stayActionTime
     *
     * @param integer $stayActionTime
     *
     * @return HandlingMessageType
     */
    public function setStayActionTime($stayActionTime)
    {
        $this->stayActionTime = $stayActionTime;

        return $this;
    }

    /**
     * Get stayActionTime
     *
     * @return integer
     */
    public function getStayActionTime()
    {
        return $this->stayActionTime;
    }

    /**
     * Set sortorder
     *
     * @param integer $sortorder
     *
     * @return HandlingMessageType
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;

        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }
    /**
     * @var string
     */
    private $reportName;

    /**
     * @var boolean
     */
    private $isReport;

    /**
     * @var integer
     */
    private $reportSortorder;

    /**
     * Set reportName
     *
     * @param string $reportName
     *
     * @return HandlingMessageType
     */
    public function setReportName($reportName)
    {
        $this->reportName = $reportName;

        return $this;
    }

    /**
     * Get reportName
     *
     * @return string
     */
    public function getReportName()
    {
        return $this->reportName;
    }

    /**
     * Set isReport
     *
     * @param boolean $isReport
     *
     * @return HandlingMessageType
     */
    public function setIsReport($isReport)
    {
        $this->isReport = $isReport;

        return $this;
    }

    /**
     * Get isReport
     *
     * @return boolean
     */
    public function getIsReport()
    {
        return $this->isReport;
    }

    /**
     * Set reportSortorder
     *
     * @param integer $reportSortorder
     *
     * @return HandlingMessageType
     */
    public function setReportSortorder($reportSortorder)
    {
        $this->reportSortorder = $reportSortorder;

        return $this;
    }

    /**
     * Get reportSortorder
     *
     * @return integer
     */
    public function getReportSortorder()
    {
        return $this->reportSortorder;
    }
}
