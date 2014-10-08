<?php

namespace ITDoors\CronBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use BCC\CronManagerBundle\Manager\CronManager;
use BCC\CronManagerBundle\Manager\Cron;

/**
 * Cron Service class
 */
class CronService
{

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct (Container $container)
    {
        $this->container = $container;
    }
    /**
     * get files and update create date in dogovor and dopdogovor
     * 
     * @return string
     */
    public function addSendEmails ()
    {
        $cm = new CronManager();
        $cron = new Cron();
        $directory = $this->container->getParameter('project.dir');
        $comment = uniqid();
        $cron->setComment($comment);

        if (!is_dir($directory . '/app/logs/cron')) {
            mkdir($directory . '/app/logs/cron', 0777);
        }
        $cron->setLogFile($directory . '/app/logs/cron/log' . $comment . '.php');
        $cron->setErrorFile($directory . '/app/logs/cron/err' . $comment . '.php');
        $cron->setCommand(
            'cd ' . $directory .
            ' && app/console swiftmailer:spool:send --env=prod' .
            ' && app/console it:doors:cron:delete ' . $comment
        );
        $cm->add($cron);

        return $comment;
    }
    /**
     * get files and update create date in dogovor and dopdogovor
     * 
     * @param integer $articleId
     * @param date    $date
     * 
     * @return string
     */
    public function sendOnly15ArticleDecision ($articleId, $date)
    {
        $cm = new CronManager();
        $cron = new Cron();
        $directory = $this->container->getParameter('project.dir');
        $comment = uniqid();
        $cron->setComment($comment);
        $cron->setMinute(date('i', $date));
        $cron->setHour(date('H', $date));
        $cron->setDayOfMonth(date('d', $date));
        $cron->setMonth(date('m', $date));
        $cron->setLogFile($directory . '/app/logs/cron/log' . $comment . '.php');
        $cron->setErrorFile($directory . '/app/logs/cron/err' . $comment . '.php');
        $cron->setCommand(
            'cd ' . $directory .
            ' && app/console lists:article:send:only:15 ' . $articleId .
            ' && app/console it:doors:cron:delete ' . $comment
        );
        $cm->add($cron);

        return $comment;
    }
    /**
     * get files and update create date in dogovor and dopdogovor
     * 
     * @param integer $articleId
     * @param date    $date
     * 
     * @return string
     */
    public function sendResultArticleDecision ($articleId, $date = false)
    {
        $cm = new CronManager();
        $cron = new Cron();
        $directory = $this->container->getParameter('project.dir');
        $comment = 'ArticleResultDecision' . $articleId;
        $cron->setComment($comment);
        if (!is_dir($directory . '/app/logs/cron')) {
            mkdir($directory . '/app/logs/cron', 0777);
        }
        if ($date) {
            $cron->setMinute($date->format('i'));
            $cron->setHour($date->format('H'));
            $cron->setDayOfMonth($date->format('d'));
            $cron->setMonth($date->format('m'));
        }
        $cron->setLogFile($directory . '/app/logs/cron/log' . $comment . '.php');
        $cron->setErrorFile($directory . '/app/logs/cron/err' . $comment . '.php');
        $cron->setCommand(
            'cd ' . $directory .
            ' && app/console lists:article:result:solution ' . $articleId .
            ' && app/console it:doors:cron:delete ' . $comment
        );
        $cm->add($cron);

        return $comment;
    }
}
