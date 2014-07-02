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

        $url = $this->container->get('router')->generate(
            'list_article_vote_decision_show',
            array('id' => $id),
            true
        );
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
                        '${lastName}$' => $article->getUser()->getLastName(),
                        '${firstName}$' => $article->getUser()->getFirstName(),
                        '${middleName}$' => $article->getUser()->getMiddleName(),
                        '${id}$' => $id,
                        '${url}$' => '<a href="' . $url  . '">' . $url . '</a>',
                        '${dateUnpublic}$' => date('d.m.Y H:i', $article->getDateUnpublick()->getTimestamp()),
                        '${datePublic}$' => date('d.m.Y H:i', $article->getDatePublick()->getTimestamp()),
                        '${title}$' => '<a href="' . $url  . '">' . $article->getTitle() . '</a>',
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
     * get files and update create date in dogovor and dopdogovor
     * 
     * @param integer $id
     * @param integer $status
     * 
     * @return string
     */
    public function sendEmailsResult($id, $status)
    {

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var VoteRepository $partys */
        $partys = $em->getRepository('ListsArticleBundle:Vote')
            ->getVoites($id);

        /** @var ArticleRepository $article */
        $article =  $em->getRepository('ListsArticleBundle:Article')
            ->find($id);

        $emailTo = $this->container->getParameter('email.from');
        $nameTo = $this->container->getParameter('name.from');
        $email = $this->container->get('it_doors_email.service');

        $url = $this->container->get('router')->generate(
            'list_article_vote_decision_show',
            array('id' => $id),
            true
        );
        if ($status == 0) {
            $status = '<span style="
                    background-color: #f3565d;
                    font-size: 12px;
                    padding: 3px 6px 3px 6px;
                    line-height: 1;
                    color: #fff;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;display: inline">'.$this->get('translator')->trans('Deflecting', array(), 'ListsArticleBundle').'</span>';
        } elseif ($status == 1) {
            $status = '<span style="
                    background-color: #45b6af;
                    font-size: 12px;
                    padding: 3px 6px 3px 6px;
                    line-height: 1;
                    color: #fff;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;
                    display: inline">'.
                    $this->get('translator')->trans('Accept', array(), 'ListsArticleBundle').'</span>';
        } elseif ($status == 2) {
            $status = '<span style="
                    background-color: #999;
                    font-size: 12px;
                    padding: 3px 6px 3px 6px;
                    line-height: 1;
                    color: #fff;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;display: inline">50/50</span>';
        }
        $table = '
            <table style="text-align:center;">
                <thead>
                    <tr>
                        <th width="5%" rowspan="1" colspan="1">
                            №
                        </th>
                        <th width="5%" tabindex="0" rowspan="1" colspan="1">
                            '.$this->container->get('translator')->trans('Autor', array(), 'ListsArticleBundle').'
                        </th>
                        <th width="5%" rowspan="1" colspan="1">
                          '.$this->container->get('translator')->trans('Date decision', array(), 'ListsArticleBundle').'
                        </th>
                        <th width="5%" tabindex="0" rowspan="1" colspan="1">
                           '.$this->container->get('translator')->trans('Decision', array(), 'ListsArticleBundle').'
                        </th>
                    </tr>
                </thead>
                <tbody>';
        $i = 1;
        foreach ($partys as $key => $party) {
            $table .= '
                <tr>
                <td>' . $i++ . '</td>
                <td>' . $party['lastName']. ' ' . $party['firstName']. ' ' . $party['middleName']. '</td>
                <td>' . (empty($party['dateCreate']) ? '' : $party['dateCreate']->format('d.m.Y H:i')) . '</td>
                <td>';
            if ($party['value'] === null) {
                $table .= '<span style="
                    background-color: #fcb322;
                    font-size: 12px;
                    padding: 3px 6px 3px 6px;
                    line-height: 1;
                    color: #fff;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;display: inline">'.
                    $this->container->get('translator')->trans(
                        'No answer',
                        array(),
                        'ListsArticleBundle'
                    ).'</span>';
            } elseif ($party['value'] == 0) {
                $table .= '<span style="
                    background-color:#f3565d;
                    font-size: 12px;
                    padding: 3px 6px 3px 6px;
                    line-height: 1;
                    color: #fff;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;display: inline">'.
                    $this->container->get('translator')->trans(
                        'Deflecting',
                        array(),
                        'ListsArticleBundle'
                    ).'</span>';
            } else {
                $table .= '<span style="
                    background-color: #45b6af;
                    font-size: 12px;
                    padding: 3px 6px 3px 6px;
                    line-height: 1;
                    color: #fff;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;
                    display: inline">'.
                    $this->container->get('translator')->trans(
                        'Accept',
                        array(),
                        'ListsArticleBundle'
                    ).'</span>';
            }
            $table .= '</td>'
                . '</tr>';

        }
        $table .= ' </tbody></table>';
        foreach ($partys as $party) {
            echo $party['firstName'] . "\t\n";

            $email->send(
                array($emailTo => $nameTo),
                'decision-result',
                array(
                    'users' => array(
                        $party['email']
                    ),
                    'variables' => array(
                        '${lastName}$' => $article->getUser()->getLastName(),
                        '${firstName}$' => $article->getUser()->getFirstName(),
                        '${middleName}$' => $article->getUser()->getMiddleName(),
                        '${id}$' => $id,
                        '${url}$' => '<a href="' . $url  . '">' . $url . '</a>',
                        '${datePublic}$' => date('d.m.Y H:i', $article->getDatePublick()->getTimestamp()),
                        '${dateUnpublic}$' => date('d.m.Y H:i', $article->getDateUnpublick()->getTimestamp()),
                        '${title}$' => '<a href="' . $url  . '">' . $article->getTitle() . '</a>',
                        '${table}$' => $table,
                        '${status}$' => $status,
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

        if ($vote['count0'] > $vote['count1']) {
            $status = 0;
        } elseif ($vote['count0'] === $vote['count1']) {
            $status = 2;
        } else {
            $status = 1;
        }

        if ($article->getDateUnpublick()->getTimestamp() <= time()) {
            $ration = new Ration();
            $ration->setValue($status);
            $ration->setArticle($article);

            $em->persist($ration);
            $em->flush();
            $this->sendEmailsResult($id, $status);
        } else {
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
