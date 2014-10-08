<?php

namespace SD\ActivityBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use SD\ActivityBundle\Interfaces\ActivityAbstract;

/**
 * OrganizationActivityService class
 */
class OrganizationActivityService extends ActivityAbstract
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getActivity()
    {
        $activity = array();

        $history = $this->container
            ->get('doctrine')->getManager()
            ->getRepository('ITDoorsHistoryBundle:History')
            ->findBy(
                array(
                    'modelName' =>'organization'
                ),
                array(
                    'createdatetime' => 'desc'
                ),
                $this->numberShowLastDays
            );
        $activity = array_merge($activity, $history);

        $activity = $this->container
            ->get('activity.sentence.maker.service')->makeSentenceHistoryActivity($activity);

        return $activity;
    }
}
