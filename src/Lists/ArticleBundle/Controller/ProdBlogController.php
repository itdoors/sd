<?php

namespace Lists\ArticleBundle\Controller;

use Lists\ArticleBundle\Controller\BaseController;
use Lists\ArticleBundle\Entity\NewsRole;
use Lists\ArticleBundle\Entity\NewsFosUser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\ArticleBundle\Entity\Article;
use Doctrine\Common\EventManager;
use Lists\ArticleBundle\Entity\ArticleRepository;
use Lists\ArticleBundle\Entity\Vote;
use Lists\ArticleBundle\Entity\Ration;
use SD\UserBundle\Entity\User;

class ProdBlogController extends BaseController
{
	protected $baseTemplate = 'ProdBlog';
	protected $articleType = 'blog';
	
	/** @var Article $filterNamespace */
	protected $filterNamespace = 'filter.namespace.article';
	
	/** @var KnpPaginatorBundle $paginator */
	protected $paginator = 'knp_paginator';

    /**
     * Renders a blog article
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
                $ratValue = $article['value'];
            }
        }
        $formView = false;
        if (!$voteValue) {
            $vote = new Vote();
            $form = $this->createFormBuilder($vote)
                ->add('value', 'choice', array(
                    'attr' => array(
                        'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                        'placeholder' => 'Vote',
                        'required' => 'required',
                        'minimumInputLength' => 0,
                        'data-empty' => $this->get('translator')->trans('Select rating', array(), 'ListsArticleBundle')
                    ),
                    'choices' => array(
                        '' => $this->get('translator')->trans('Select rating', array(), 'ListsArticleBundle'),
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
                        if (!$rationResult) {
                            $rationResult = $vR->getVoteForArticle($id);
                            if (!empty($rationResult['countVote'])) {
                                $rationResult['average'] =
                                    round($rationResult['sumVote'] / $rationResult['countVote'], 2);
                                $ratValue = $article['value'];
                            }
                        }
                        $rationResult['sumVote'] = $rationResult['sumVote']+$value;
                        $rationResult['countVote'] = $rationResult['countVote']+1;
                        $rationResult['average'] = round($rationResult['sumVote']  / $rationResult['countVote'], 2);
                        $ratValue = round(
                            (
                                ($rationResult['countVote'] - 1)*
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

                return $this->redirect($this->generateUrl('list_article_blog_show', array('id' => $id)));
            }
            $formView = $form->createView();
        }


        /** NewFosUser $nfu*/
        $articleEntity = $aR->find($id);
        $nfu = $em->getRepository('ListsArticleBundle:NewsFosUser')->findOneBy(array('news' => $articleEntity, 'user' => $user));
        
        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'item' => $article,
                'vote' => $voteValue,
                'form' => $formView,
                'rationResult' => $rationResult,
                'votes' => $votes,
                'ratValue' => $ratValue,
        		'viewed' => $nfu->getViewed()
        ));
    }

	/**
	 * @return string
	 */
	public function listAction()
	{
		$filterNamespace = $this->container->getParameter($this->getNamespace());
	
		$em = $this->getDoctrine()->getManager();
		$artivles = $em->getRepository('ListsArticleBundle:Article')->getBlog($this->getUser());
	
// 		$namespasePagin = $filterNamespace . 'P';
// 		$page = $this->getPaginator($namespasePagin);
// 		if (!$page) {
// 			$page = 1;
// 		}
	
// 		$paginator = $this->container->get($this->paginator);
// 		$artivles['articles']->setHint($this->paginator . '.count', $artivles['count']);
// 		$pagination = $paginator->paginate($artivles['articles'], $page, 20);
	
		return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':list.html.twig', array(
				'items' => $artivles//$pagination,
// 				'namespasePagin' => $namespasePagin
		));
	}
	
    /**
     * Sets an article viewed
     *
     * @return string
     */
    public function setViewedAction($id)
    {
    	$user = $this->getUser();
    	$em = $this->getDoctrine()->getManager();
    	$article = $em->getRepository('ListsArticleBundle:Article')->find($id);
    	$newsFosUsers = $em->getRepository('ListsArticleBundle:NewsFosUser')->findBy(array('news' => $article, 'user' => $user));
    	
    	foreach ($newsFosUsers as $nfu) {
    		$nfu->setViewed(new \DateTime(date('Y-m-d H:i:s')));
    		$em->persist($nfu);
    	}
    	
    	$em->flush();
        return $this->redirect($this->generateUrl('list_article_blog'));
    }
    
    public function shortListAction() 
    {
    	$em = $this->getDoctrine()->getManager();
    	$artivles = $em->getRepository('ListsArticleBundle:Article')->getBlog($this->getUser());
    	
    	return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':shortList.html.twig', array(
    			'items' => $artivles['articles']->getArrayResult()
    	));
    }
    
    /**
     * Adds new article into blog
     *
     * @param Request $request
     * 
     *
     * @return string
     */
    public function addAction(Request $request)
    {
    	/** @var EntityManager $em */
    	$em = $this->getDoctrine()->getManager();
    	
    	$form = $this->createForm('article'.ucfirst($this->articleType).'Form');
    	$form->handleRequest($request);

    	if ($form->isValid()) {
    		try {
    			$party = $form->getData();

    			if ($this->getUser()->hasRole('ROLE_ARTICLEADMIN')) {
    				$user = $em->getRepository('SDUserBundle:User')->find($party->getUserId());
    				$party->setUser($user);
    			} else {
    				$party->setUser($this->getUser());
    			}
    			$party->setType($this->articleType);
    			if (method_exists($party, 'getDatePublick') && $party->getDatePublick() != '') {
    				$party->setDatePublick(new \DateTime($party->getDatePublick()));
    			}
    			$party->setDateCreate(new \DateTime(date('Y-m-d H:i:s')));
    			$em->persist($party);
    			
				$part = $request->request->get($form->getName());
				$roles = $part['roles'];
				
    			foreach ($roles as $role) {	
    				$fetchedRole = $em->getRepository('SDUserBundle:Group')->find($role);
    				
    				$news_role = new NewsRole();
    				$news_role->setNews($party);
    				$news_role->setRoles($fetchedRole);
    				
    				$fetchedUsers = $fetchedRole->getUsers()->toArray();
    				
    				foreach ($fetchedUsers as $user) {
    					$nfu = new NewsFosUser();
    					$nfu->setNews($news_role->getNews());
    					$nfu->setUser($user);
    					$em->persist($nfu);
    				}
    				$em->persist($news_role);
    			}
    			$em->flush();
    		} catch (\Exception $e) {
    			$em->close();
    			throw $e;
    		}
    
    		return $this->redirect($this->generateUrl('list_article_blog'));
    	}
    
    	return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':add.html.twig', array(
    			'form' => $form->createView(),
    	));
    }

}
