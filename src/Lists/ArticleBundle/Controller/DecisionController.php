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

/**
 * Class SolutionsController
 */
class DecisionController extends BaseController
{

    protected $baseTemplate = 'Decision';
    protected $articleType = 'decision';

    /** @var Article $filterNamespace */
    protected $filterNamespace = 'filter.namespace.article';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';


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

        $article = $aR->getArticle($id);

        $voteValue = $aR->getVote($id, $user->getId());

        /** VoteRepository $vR */
        $vR = $em->getRepository('ListsArticleBundle:Vote');

        $votes = false;

        $rationResult = false;
        $ratValue = 0;
        if ($user->hasRole('ROLE_ARTICLEADMIN')) {
            $votes = $vR->getVoites($id);
            $rationResult = $vR->getVoteForArticle($id);
            if (!empty($rationResult['countVote'])) {
                $rationResult['average'] =  round($rationResult['sumVote'] / $rationResult['countVote'], 2);
                $ratValue = round(
                    (
                        $rationResult['countVote'] *
                        $rationResult['average'] -
                        $rationResult['average']
                    )+1,
                    2
                );
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

                    $ration = $em->getRepository('ListsArticleBundle:Ration')
                        ->findOneBy(array( 'articleId' => $id ));
                    if (!$ration) {
                        $ration = new Ration();
                        $ration->setArticle($em->getRepository('ListsArticleBundle:Article')->find($id));
                    }

                    if (in_array($value, array(1, 2, 3, 4, 5))) {
                        $rationResult['sumVote'] += $value;
                        $rationResult['countVote'] += 1;
                        $rationResult['average'] = round($rationResult['sumVote']  / $rationResult['countVote'], 2);
                        $ratValue = round(
                            (
                                $rationResult['countVote'] *
                                $rationResult['average']-
                                $rationResult['average']
                            )+1,
                            2
                        );

                        $ration->setValue($ratValue);
                        $em->persist($vote);
                        $em->persist($ration);
                        $em->flush();
                        if (!$user->hasRole('ROLE_ARTICLEADMIN')) {
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

                return $this->redirect($this->generateUrl('list_article_vote_history_show', array('id' => $id)));
            }
            $formView = $form->createView();
        }

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'item' => $article,
                'vote' => $voteValue,
                'form' => $formView,
                'rationResult' => $rationResult,
                'votes' => $votes,
                'ratValue' => $ratValue
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
        $form = $this->createForm('article'.ucfirst($this->articleType).'Form');

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            try {
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
}
