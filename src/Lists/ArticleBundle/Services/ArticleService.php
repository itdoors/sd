<?php

namespace Lists\ArticleBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Response;
use BCC\CronManagerBundle\Manager\CronManager;
use BCC\CronManagerBundle\Manager\Cron;
use Lists\ArticleBundle\Entity\Ration;

/**
 * Invoice Service class
 */
class ArticleService
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
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * get files and update create date in dogovor and dopdogovor
     * 
     * @param integer $id
     * 
     * @return string
     */
    public function sendEmails($id)
    {

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var VoteRepository $partys */
        $partys = $em->getRepository('ListsArticleBundle:Vote')
            ->getVoitesFor15($id);

        /** @var ArticleRepository $article */
        $article =  $em->getRepository('ListsArticleBundle:Article')
            ->find($id);

        $emailTo = $this->container->getParameter('email.from');
        $nameTo = $this->container->getParameter('name.from');
        $email = $this->container->get('it_doors_email.service');

        foreach ($partys as $party) {
            echo $party['firstName'] . "\t\n";

            $email->send(
                array($emailTo => $nameTo),
                'decision-making-only-15-minutes',
                array(
                    'users' => array(
                        $party['email']
                    ),
                    'variables' => array(
                        '${lastName}$' => $party['lastName'],
                        '${firstName}$' => $party['firstName'],
                        '${middleName}$' => $party['middleName'],
                        '${id}$' =>
                        '<a href="' . $this->container->get('router')->generate(
                            'list_article_vote_decision_show',
                            array('id' => $id),
                            true
                        )
                        . '">' . $id . '</a>',
                        '${dateUnpublic}$' => date('d.m.Y H:i', $article->getDateUnpublick()->getTimestamp()),
                    )
                )
            );
        }
        $cm = new CronManager();
        $cron = new Cron();
        $directory = $this->container->getParameter('project.dir');
        $comment = uniqid();
        $cron->setComment($comment);
        if (!is_dir($directory.'/app/logs/cron')) {
            mkdir($directory.'/app/logs/cron', 0777);
        }
        $cron->setLogFile($directory.'/app/logs/cron/log'.$comment.'.php');
        $cron->setErrorFile($directory.'/app/logs/cron/err'.$comment.'.php');
        $cron->setCommand(
            'cd ' . $directory .
            ' && app/console swiftmailer:spool:send --env=prod' .
            ' && app/console it:doors:cron:delete ' . $comment
        );
        $cm->add($cron);
    }
    
    /**
     * resultSolutions
     * 
     * @param integer $id
     * 
     * @return string
     */
    public function resultSolutions($id)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        /** @var ArticleRepository $article */
        $article = $em->getRepository('ListsArticleBundle:Article')->find($id);
        /** @var VoteRepository $vote */
        $vote = $em->getRepository('ListsArticleBundle:Vote')->getVoteForArticleDecision($id);
     
        if ( $vote['count0'] > $vote['count1']) {
            $status = 0;
        } elseif ($vote['count0'] === $vote['count1']) {
            $status = 2;
        }else{
            $status = 1;
        }

        if($article->getDateUnpublick()->getTimestamp() <= time()){
            $ration = new Ration();
            $ration->setValue($status);
            $ration->setArticle($article);

            $em->persist($ration);
            $em->flush();
        }else{
            $status = 'Not completed';
            // result
            $cm = new CronManager();
            $cron = new Cron();
            $directory = $this->container->getParameter('project.dir');
            $comment = uniqid();
            $cron->setComment($comment);
            if (!is_dir($directory.'/app/logs/cron')) {
                mkdir($directory.'/app/logs/cron', 0777);
            }
            $cron->setLogFile($directory.'/app/logs/cron/log'.$comment.'.php');
            $cron->setErrorFile($directory.'/app/logs/cron/err'.$comment.'.php');
            $cron->setCommand(
                'cd ' . $directory .
                ' && lists:article:result:solution ' . $party->getId() .
                ' && app/console it:doors:cron:delete ' . $comment
            );
            $cm->add($cron);
        }
        return $status;
    }
}
