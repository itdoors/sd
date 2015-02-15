<?php

namespace SD\ActivityBundle\Services;

use SD\TaskBundle\Entity\Comment;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use ITDoors\HistoryBundle\Entity\History;
use SD\ActivityBundle\Classes\ActivityHolder;

/**
 * ActivitySentenceMaker class
 */
class ActivitySentenceMaker
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
     * @param History[] $historyActivities
     *
     * @return array
     */
    public function makeSentenceHistoryActivity($historyActivities)
    {
        $activities = array();
        if (is_array($historyActivities)) {

        } else {
            $historyActivities = array($historyActivities);
        }
        foreach ($historyActivities as $history) {
            if ($history instanceof History) {
                $activities[] = $this->getActivityFromHistory($history);
            }
        }

        return $activities;
    }

    /**
     * @param History $history
     *
     * @return ActivityHolder
     */
    private function getActivityFromHistory($history)
    {
        $action = $history->getAction();
        $user = $history->getUser();
        $date = $history->getCreatedatetime();
        $fieldName = $history->getFieldName();
        $modelName = $history->getModelName();
        $value = $history->getValue();

        $message = $user.' '.$action.' '.$fieldName.' '.$modelName.' '.$value;

        $returnActivity = new ActivityHolder($message, $date);

        return $returnActivity;
    }


    /**
     * @param Comment[] $taskActivities
     *
     * @return array
     */
    public function makeSentenceTaskActivity($taskActivities)
    {
        $activities = array();
        if (!is_array($taskActivities)) {
            $taskActivities = array($taskActivities);
        }
        foreach ($taskActivities as $comment) {
            if ($comment instanceof Comment) {
                $activities[] = $this->getActivityFromCommentTask($comment);
            }
        }

        return $activities;
    }

    /**
     * @param Comment $comment
     *
     * @return ActivityHolder
     */
    private function getActivityFromCommentTask($comment)
    {
        $value = $comment->getValue();
        $value = str_replace('<br>', "\n", $value); //need to fix this ASAP. govnogod...
        $user = $comment->getUser();
        $date = $comment->getCreateDatetime();

        $task = $this->container->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:Task')
            ->find($comment->getModelId());

        $taskTitle = $task->getTitle();
        $taskId = $task->getId();

        $message = $taskTitle.' (ID: '.$taskId.')'."\n ".$user.': '.$value;

        $returnActivity = new ActivityHolder($message, $date);

        return $returnActivity;
    }
}
