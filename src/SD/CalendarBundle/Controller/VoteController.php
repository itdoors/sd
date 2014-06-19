<?php

namespace SD\CalendarBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SD\CalendarBundle\Entity\Article;
use Doctrine\Common\EventManager;
use SD\CalendarBundle\Entity\ArticleRepository;
use SD\CalendarBundle\Entity\Vote;
use SD\CalendarBundle\Entity\Ration;
use SD\UserBundle\Entity\User;

/**
 * Class VoteController
 */
class VoteController extends BaseFilterController
{

    protected $baseTemplate = 'Vote';

    /** @var Article $filterNamespace */
    protected $filterNamespace = 'filter.namespace.article';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function historyAction()
    {
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':history.html.twig');
    }

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

        /** ArticleRepository $artivles */
        $artivles = $em->getRepository('SDCalendarBundle:Article')->getArticles();

        $namespasePagin = $filterNamespace . 'P';
        $page = $this->getPaginator($namespasePagin);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $artivles['articles']->setHint($this->paginator . '.count', $artivles['count']);
        $pagination = $paginator->paginate($artivles['articles'], $page, 20);

        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':list.html.twig', array(
                'items' => $pagination,
                'namespasePagin' => $namespasePagin
        ));
    }

    /**
     * Renders template holder for calendar
     *
     * @param Request $request
     * 
     * @return string
     */
    public function addHistoryAction(Request $request)
    {
        $article = new Article();
        $router = $this->get('router');
        $form = $this->createFormBuilder($article)
            ->add('userId', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    )),
                    'placeholder' => 'Enter fio'
                )
            ))
            ->add('datePublick', 'text', array())
            ->add('title', 'text', array())
            ->add('textShort', 'textarea', array())
            ->add('text', 'textarea', array())
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            /** @var Connection $connection */
            $connection = $this->getDoctrine()->getConnection();

            $connection->beginTransaction();

            try {
                $formData = $request->request->get($form->getName());
                $userId = $formData['userId'];
                $user = $em->getRepository('SDUserBundle:User')->find($userId);

                $party = new Article();

                $party->setUser($user);
                $party->setType('article');

                if (!empty($formData['datePublick'])) {
                    $party->setDatePublick(new \DateTime($formData['datePublick']));
                }
                $party->setDateCreate(new \DateTime(date('Y-m-d H:i:s')));
                $party->setTitle($formData['title']);
                $party->setTextShort($formData['textShort']);
                $party->setText($formData['text']);


                $em->persist($party);

                $em->flush();

                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollBack();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('sd_calendar_vote_history'));
        }

        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':addHistory.html.twig', array(
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
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        /** @var EventManager $em */
        $em = $this->getDoctrine()->getManager();

        /** ArticleRepository $aR */
        $aR = $em->getRepository('SDCalendarBundle:Article');

        /** User $user */
        $user = $this->getUser();

        $article = $aR->getArticle($id);

        $voteValue = $aR->getVote($id, $user->getId());

        /** VoteRepository $vR */
        $vR = $em->getRepository('SDCalendarBundle:Vote');

        $votes = false;

        $rationResult = false;
        $ratValue = 0;
        if ($user->hasRole('ROLE_ARTICLEADMIN')) {
            $votes = $vR->getVoites($id);
            $rationResult = $vR->getVoteForArticle($id);
            if (!empty($rationResult['countVote'])) {
                $ratValue = round($rationResult['countVote'] / ($rationResult['sumVote'] / $rationResult['countVote']), 2);
            }
        }
        $formView = false;

        if (!$voteValue) {
            $vote = new Vote();
            //$router = $this->get('router');
            $form = $this->createFormBuilder($vote)
                ->add('value', 'choice', array(
                    'attr' => array(
                        'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                        'placeholder' => 'Vote'
                    ),
                    'choices' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5'
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

                    $vote = new Vote();
                    $vote->setUser($user);
                    $vote->setModelName('article');
                    $vote->setModelid($id);
                    $vote->setValue($value);
                    $vote->setDateCreate(new \DateTime(date('Y-m-d H:i:s')));

                    $ration = $em->getRepository('SDCalendarBundle:Ration')
                        ->findOneBy(array( 'articleId' => $id ));
                    if (!$ration) {
                        $ration = new Ration();
                        $ration->setArticle($em->getRepository('SDCalendarBundle:Article')->find($id));
                    }
                    $ratValue = round(($rationResult['countVote'] + 1) / (($rationResult['sumVote'] + $value) / ($rationResult['countVote'] + 1)), 2);

                    $ration->setValue($ratValue);

                    if (in_array($value, array(1, 2, 3, 4, 5))) {
                        $em->persist($vote);
                        $em->persist($ration);
                        $em->flush();
                        if ($user->hasRole('ROLE_ARTICLEADMIN')) {
                            $rationResult = $vR->getVoteForArticle($id);
                        } else {
                            $rationResult = false;
                            $ratValue = false;
                        }
                    }
                    $connection->commit();
                } catch (\Exception $e) {
                    $connection->rollBack();
                    $em->close();
                    throw $e;
                }

                return $this->redirect($this->generateUrl('sd_calendar_vote_history_show', array('id' => $id)));
            }
            $formView = $form->createView();
        }

        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'item' => $article,
                'vote' => $voteValue,
                'form' => $formView,
                'rationResult' => $rationResult,
                'votes' => $votes,
                'ratValue' => $ratValue
        ));
    }
}
