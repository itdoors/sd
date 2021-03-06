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
//            $connection = $this->getDoctrine()->getConnection();
//            $connection->beginTransaction();

            try {
                $formData = $request->request->get($form->getName());

                /** @var Article $party */
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

                    $url = $this->generateUrl(
                        'list_article_vote_decision_show',
                        array('id' => $party->getId()),
                        true
                    );
                    $email->send(
                        array($emailTo => $nameTo),
                        'decision-making',
                        array(
                            'users' => array(
                                $user->getEmail()
                            ),
                            'variables' => array(
                                '${lastName}$' => $party->getUser()->getLastName(),
                                '${firstName}$' => $party->getUser()->getFirstName(),
                                '${middleName}$' => $party->getUser()->getMiddleName(),
                                '${id}$' => $party->getId(),
                                '${datePublic}$' => date('d.m.Y H:i', $party->getDatePublick()->getTimestamp()),
                                '${dateUnpublic}$' => date('d.m.Y H:i', $party->getDateUnpublick()->getTimestamp()),
                                '${title}$' => '<a href="' . $url . '">' . $party->getTitle() . '</a>',
                                '${url}$' => '<a href="' . $url . '">' . $url . '</a>',
                            )
                        )
                    );
                }
//                $connection->commit();
                $cron = $this->container->get('it_doors_cron.service');
                $cron->addSendEmails();
                // send for 15 min
                $datePublich = $party->getDatePublick()->getTimestamp();
                $dateUnpublick = $party->getDateUnpublick()->getTimestamp()-900;
                if ($datePublich < $dateUnpublick) {
                    $cron->sendOnly15ArticleDecision($party->getId(), $dateUnpublick);
                }
                // result
                $cron->sendResultArticleDecision($party->getId(), $party->getDateUnpublick());

            } catch (\Exception $e) {
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
                    $countLast = $vR->countLast($id);
                    if ($countLast == 0) {
                        $cron = $this->container->get('it_doors_cron.service');
                        $cron->sendResultArticleDecision($id);
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
