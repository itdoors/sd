<?php

namespace Lists\ArticleBundle\Controller;

use Lists\ArticleBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\ArticleBundle\Entity\Article;
use Doctrine\Common\EventManager;
use Lists\ArticleBundle\Entity\ArticleRepository;
use Lists\ArticleBundle\Entity\Vote;
use Lists\ArticleBundle\Entity\Ration;
use SD\UserBundle\Entity\User;
use BCC\CronManagerBundle\Manager\CronManager;
use BCC\CronManagerBundle\Manager\Cron;

/**
 * Class SolutionsController
 */
class DecisionController extends BaseController
{

    /** @var Decision */
    protected $baseTemplate = 'Decision';

    /** @var decision */
    protected $articleType = 'decision';

    /** @var Article $filterNamespace */
    protected $filterNamespace = 'filter.namespace.article';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function listAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        /** @var EventManager $em */
        $em = $this->getDoctrine()->getManager();

        $method = 'get' . ucfirst($this->articleType);
        /** ArticleRepository $artivles */
        if ($this->getUser()->hasRole('ROLE_ARTICLEADMIN')) {
            $userId = false;
        } else {
            $userId = $this->getUser()->getId();
        }
        $artivles = $em->getRepository('ListsArticleBundle:Article')->$method($userId);

        $namespasePagin = $filterNamespace . 'P';
        $page = $this->getPaginator($namespasePagin);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $artivles['articles']->setHint($this->paginator . '.count', $artivles['count']);
        $pagination = $paginator->paginate($artivles['articles'], $page, 20);

        $users = array();
        foreach ($pagination as $val) {
            /** @var User */
            $users[$val['id']] = $em
                    ->getRepository('ListsArticleBundle:Vote')->getVoites($val['id']);
        }

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':list.html.twig', array(
                'items' => $pagination,
                'namespasePagin' => $namespasePagin,
                'users' => $users
        ));
    }

    /**
     * Renders template holder for calendar
     *
     * @param Request $request
     * 
     * @return string
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm('article' . ucfirst($this->articleType) . 'Form');
        if ($this->getUser()->hasRole('ROLE_ARTICLEADMIN')) {
            $form->add('userId', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'data-url' => $this->generateUrl('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $this->generateUrl('sd_common_ajax_user_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter fio'
                )
            ));
        }
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            /** @var Connection $connection */
            $connection = $this->getDoctrine()->getConnection();
            $connection->beginTransaction();

            try {
                $formData = $request->request->get($form->getName());

                $party = $form->getData();
                if ($this->getUser()->hasRole('ROLE_ARTICLEADMIN')) {
                    $user = $em->getRepository('SDUserBundle:User')->find($party->getUserId());
                    $party->setUser($user);
                } else {
                    $party->setUser($this->getUser());
                }

                $party->setType($this->articleType);
                $party->setDateCreate(new \DateTime(date('Y-m-d H:i:s')));
                $party->setDatePublick(new \DateTime(date('Y-m-d H:i:s')));
                $party->setDateUnpublick(new \DateTime($party->getDateUnpublick()));

                $em->persist($party);
                $em->flush();
                $em->refresh($party);

                $users = explode(',', $formData['users'] . ',' . $party->getUser()->getId());
                foreach ($users as $userId) {
                    $user = $em->getRepository('SDUserBundle:User')->find($userId);
                    $vote = new Vote();
                    $vote->setUser($user);
                    $vote->setModelName('article');
                    $vote->setModelId($party->getId());
                    $em->persist($vote);
                    $em->flush();

                    $emailTo = $this->container->getParameter('email.from');
                    $nameTo = $this->container->getParameter('name.from');

                    $email = $this->get('it_doors_email.service');
                    $email->send(
                        array($emailTo => $nameTo), 'decision-making', array(
                        'users' => array(
                            $user->getEmail()
                        ),
                        'variables' => array(
                            '${lastName}$' => $user->getLastName(),
                            '${firstName}$' => $user->getFirstName(),
                            '${middleName}$' => $user->getMiddleName(),
                            '${id}$' =>
                            '<a href="' . $this->generateUrl(
                                'list_article_vote_decision_show', array('id' => $party->getId()), true
                            )
                            . '">' . $party->getId() . '</a>',
                            '${dateUnpublic}$' => date('d.m.Y H:i', $party->getDateUnpublick()->getTimestamp()),
                        )
                        )
                    );
                }
                $connection->commit();

                // send
                $cm = new CronManager();
                $cron = new Cron();
                $directory = $this->container->getParameter('project.dir');
                $comment = uniqid();
                $cron->setComment($comment);
                if (!is_dir($directory . '/app/logs/cron')) {
                    mkdir($directory . '/app/logs/cron', 0777);
                }
                $cron->setLogFile($directory.'/app/logs/cron/log'.$comment.'.php');
                $cron->setErrorFile($directory.'/app/logs/cron/err'.$comment.'.php');
                $cron->setCommand(
                    'cd ' . $directory .
                    ' && app/console swiftmailer:spool:send --env=prod' .
                    ' && app/console it:doors:cron:delete ' . $comment
                );
                $cm->add($cron);

                // send for 15 min
                $datePublich = mktime(
                    date('H', $party->getDatePublick()->getTimestamp()),
                    date('i', $party->getDatePublick()->getTimestamp()),
                    date('s', $party->getDatePublick()->getTimestamp()),
                    date('m', $party->getDatePublick()->getTimestamp()),
                    date('d', $party->getDatePublick()->getTimestamp()),
                    date('y', $party->getDatePublick()->getTimestamp())
                    );
                $dateUnpublick = mktime(
                    date('H', $party->getDateUnpublick()->getTimestamp()),
                    date('i', $party->getDateUnpublick()->getTimestamp())-16,
                    0,
                    date('m', $party->getDateUnpublick()->getTimestamp()),
                    date('d', $party->getDateUnpublick()->getTimestamp()),
                    date('y', $party->getDateUnpublick()->getTimestamp())
                );

                if ($datePublich < $dateUnpublick) {
                    $cm = new CronManager();
                    $cron = new Cron();
                    $comment = uniqid();
                    $cron->setComment($comment);
                    $cron->setMinute(date('i', $dateUnpublick));
                    $cron->setHour(date('H', $dateUnpublick));
                    $cron->setDayOfMonth(date('d', $dateUnpublick));
                    $cron->setMonth(date('m', $dateUnpublick));
                    $cron->setLogFile($directory.'/app/logs/cron/log'.$comment.'.php');
                    $cron->setErrorFile($directory.'/app/logs/cron/err'.$comment.'.php');
                    $cron->setCommand(
                        'cd '.$directory .
                        ' app/console lists:article:send:only:15 '. $party->getId() .
                        ' && app/console it:doors:cron:delete ' . $comment
                    );
                    $cm->add($cron);
                }
                
                // result
                $cm = new CronManager();
                $cron = new Cron();
                $directory = $this->container->getParameter('project.dir');
                $comment = uniqid();
                $cron->setComment($comment);
                if (!is_dir($directory.'/app/logs/cron')) {
                    mkdir($directory.'/app/logs/cron', 0777);
                }
                $cron->setMinute(date('i', $party->getDateUnpublick()->format('i')));
                $cron->setHour(date('H', $party->getDateUnpublick()->format('H')));
                $cron->setDayOfMonth(date('d', $party->getDateUnpublick()->format('d')));
                $cron->setMonth(date('m', $party->getDateUnpublick()->format('m')));
                $cron->setLogFile($directory.'/app/logs/cron/log'.$comment.'.php');
                $cron->setErrorFile($directory.'/app/logs/cron/err'.$comment.'.php');
                $cron->setCommand(
                    'cd ' . $directory .
                    ' && lists:article:result:solution ' . $party->getId() .
                    ' && app/console it:doors:cron:delete ' . $comment
                );
                $cm->add($cron);

            } catch (\Exception $e) {
                $connection->rollBack();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('list_article_vote_decision'));
        }

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':add.html.twig', array(
                'form' => $form->createView(),
        ));
    }

    /**
     * Renders template holder for calendar
     *
     * @param Request $request
     * @param integer $id
     * 
     * @return string
     */
    public function showAction(Request $request, $id)
    {
        /** @var EventManager $em */
        $em = $this->getDoctrine()->getManager();

        /** ArticleRepository $aR */
        $aR = $em->getRepository('ListsArticleBundle:Article');

        /** User $user */
        $user = $this->getUser();

        /** VoteRepository $vR */
        $vR = $em->getRepository('ListsArticleBundle:Vote');

        $article = $aR->getArticle($id);
        $voteValue = $aR->getVote($id, $user->getId());

        $votes = $vR->getVoites($id);
        $rationResult = $vR->getVoteForArticleDecision($id);

        $formView = false;
        if ($voteValue && $voteValue[0]['value'] === null && $article['dateUnpublick']->getTimestamp() > time()) {
            $vote = new Vote();
            $form = $this->createFormBuilder($vote)
                ->add('value', 'choice', array(
                    'attr' => array(
                        'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                        'placeholder' => 'Vote'
                    ),
                    'translation_domain' => 'ListsArticleBundle',
                    'choices' => array(
                        '1' => 'Accept',
                        '0' => 'Deflecting'
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                /** @var Connection $connection */
                $connection = $this->getDoctrine()->getConnection();

                $connection->beginTransaction();

                try {
                    $formData = $request->request->get($form->getName());
                    $user = $this->getUser();
                    $value = $formData['value'];

                    $vote = $vR->find($voteValue[0]['id']);
                    $vote->setValue($value);
                    $vote->setDateCreate(new \DateTime(date('Y-m-d H:i:s')));
                    $em->persist($vote);
                    $em->flush();

                    $rationResult = $vR->getVoteForArticleDecision($id);

                    $ration = $em->getRepository('ListsArticleBundle:Ration')
                        ->findOneBy(array('articleId' => $id));
                    if (!$ration) {
                        $ration = new Ration();
                        $ration->setArticle($em->getRepository('ListsArticleBundle:Article')->find($id));
                    }

                    if (in_array($value, array(0, 1))) {

                        $ration->setValue($rationResult['count0'] > $rationResult['count1'] ? 0 : 1);
                        $em->persist($vote);
                        $em->persist($ration);
                        $em->flush();
                        if (!$user->hasRole('ROLE_ARTICLEADMIN')) {
                            $rationResult = false;
                        }
                    }
                    $connection->commit();
                } catch (\Exception $e) {
                    $connection->rollBack();
                    $em->close();
                    throw $e;
                }

                return $this->redirect($this->generateUrl('list_article_vote_decision_show', array('id' => $id)));
            }
            $formView = $form->createView();
        }

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'item' => $article,
                'vote' => $voteValue,
                'form' => $formView,
                'rationResult' => $rationResult,
                'votes' => $votes
        ));
    }
}
