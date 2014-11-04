<?php

namespace SD\ActivityBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use SD\ActivityBundle\Interfaces\ActivityAbstract;

/**
 * TaskActivityService class
 */
class TaskActivityService extends ActivityAbstract
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

        $user = $this->container->get('security.context')->getToken()->getUser();

        $tasksAvailable =  $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:TaskUserRole')
            ->getTasksAvailable($user);

        $idsAvailable = array();

        foreach ($tasksAvailable as $taskAvailable) {
            $idsAvailable[] = $taskAvailable['id'];
        }

        $minDate = new \DateTime();
        $minDate->sub(new \DateInterval('P'.$this->numberShowLastDays.'D'));

        $comment = $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:Comment')
            ->getCommentsForTasksId($idsAvailable, $minDate);

        $activity = $comment;
        //var_dump(count($activity));
        $activity = $this->container
            ->get('activity.sentence.maker.service')->makeSentenceTaskActivity($activity);


        return $activity;
    }
}
