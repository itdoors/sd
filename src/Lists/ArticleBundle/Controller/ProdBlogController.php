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

/**
 * Class ProdBlogController
 */
class ProdBlogController extends BaseController
{

    protected $baseTemplate = 'ProdBlog';

    protected $articleType = 'blog';

    /**
     * @var Article $filterNamespace
     */
    protected $filterNamespace = 'filter.namespace.article';

    /**
     * @var KnpPaginatorBundle $paginator
     */
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
        /**
         * @var EventManager $em
         */
        $em = $this->getDoctrine()->getManager();

        /**
         * ArticleRepository $aR
         */
        $aR = $em->getRepository('ListsArticleBundle:Article');

        /**
         * User $user
         */
        $user = $this->getUser();

        $article = $aR->getArticle($id);
        $voteValue = $aR->getVote($id, $user->getId());

        /**
         * VoteRepository $vR
         */
        $vR = $em->getRepository('ListsArticleBundle:Vote');
        $votes = false;
        $rationResult = false;
        $ratValue = 0;
        if ($user->hasRole('ROLE_ARTICLEADMIN')) {
            $votes = $vR->getVoites($id);
            $rationResult = $vR->getVoteForArticle($id);
            if (! empty($rationResult['countVote'])) {
                $rationResult['average'] = round($rationResult['sumVote'] / $rationResult['countVote'], 2);
                $ratValue = $article['value'];
            }
        }
        $formView = false;
        if (! $voteValue) {
            $vote = new Vote();
            $form = $this->createFormBuilder($vote)
                ->add('value', 'choice', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field control-label col-md-3',
                    'placeholder' => 'Vote',
                    'required' => 'required',
                    'minimumInputLength' => 0,
                    'data-empty' => $this->get('translator')
                        ->trans('Select rating', array(), 'ListsArticleBundle')
                ),
                'choices' => array(
                    '' => $this->get('translator')
                        ->trans('Select rating', array(), 'ListsArticleBundle'),
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
                /**
                 *
                 * @var Connection $connection
                 */
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

                    $ration = $em->getRepository('ListsArticleBundle:Ration')->findOneBy(array(
                        'articleId' => $id
                    ));
                    if (! $ration) {
                        $ration = new Ration();
                        $ration->setArticle($em->getRepository('ListsArticleBundle:Article')->find($id));
                    }

                    if (in_array($value, array(
                        1,
                        2,
                        3,
                        4,
                        5
                    ))) {
                        if (! $rationResult) {
                            $rationResult = $vR->getVoteForArticle($id);
                            if (! empty($rationResult['countVote'])) {
                                $rationResult['average'] =
                                    round($rationResult['sumVote'] / $rationResult['countVote'], 2);
                                $ratValue = $article['value'];
                            }
                        }
                        $rationResult['sumVote'] = $rationResult['sumVote'] + $value;
                        $rationResult['countVote'] = $rationResult['countVote'] + 1;
                        $rationResult['average'] = round($rationResult['sumVote'] / $rationResult['countVote'], 2);
                        $ratValue = round((($rationResult['countVote'] - 1) * $rationResult['average']) + 1, 2);

                        $ration->setValue($ratValue);
                        $em->persist($vote);
                        $em->persist($ration);
                        $em->flush();
                        if (! $user->hasRole('ROLE_ARTICLEADMIN')) {
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

                return $this->redirect($this->generateUrl('list_article_blog_show', array(
                    'id' => $id
                )));
            }
            $formView = $form->createView();
        }

        /**
         * NewFosUser $nfu
         */
        $articleEntity = $aR->find($id);
        $nfu = $em->getRepository('ListsArticleBundle:NewsFosUser')->findOneBy(array(
            'news' => $articleEntity,
            'user' => $user
        ));

        /**
         * NewRole $nr
         */
        $nr = $em->getRepository('ListsArticleBundle:NewsRole')->findOneBy(array(
            'news' => $articleEntity
        ));

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':show.html.twig', array(
            'item' => $article,
            'vote' => $voteValue,
            'form' => $formView,
            'rationResult' => $rationResult,
            'votes' => $votes,
            'ratValue' => $ratValue,
            'viewed' => $nfu->getViewed(),
            'voteable' => $nr->getVote()
        ));
    }

    /**
     * @return string
     */
    public function listAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $em = $this->getDoctrine()->getManager();
        $newNFUs = $em->getRepository('ListsArticleBundle:NewsFosUser')->findBy(array(
            'user' => $this->getUser()
        ));
        $items = [];
        foreach ($newNFUs as $nfu) {
            $item = [
                'article' => $nfu->getNews(),
                'viewed' => $nfu->getViewed()
            ];
            if (! in_array($item, $items)) {
                $items[] = $item;
            }
        }
        usort($items, array(
            "Lists\ArticleBundle\Controller\ProdBlogController",
            "mysort"
        ));

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':list.html.twig', array(
            'items' => $items
        ));
    }

    /**
     * Sorting for listAction()
     * 
     * @param array $a            
     * @param array $b            
     *
     * @return integer
     */
    private function mysort($a, $b)
    {
        if (! $a['viewed'] && $b['viewed']) {
            return - 1;
        } elseif ($a['viewed'] && ! $b['viewed']) {
            return 1;
        }
        $a = $a['article']->getDatePublick();
        $b = $b['article']->getDatePublick();
        if ($a == $b) {
            return 0;
        } else {
            return ($a > $b) ? - 1 : 1;
        }
    }

    /**
     * Sets an article viewed
     * 
     * @param integer $id
     * 
     * @return string
     */
    public function setViewedAction($id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('ListsArticleBundle:Article')->find($id);
        $newsFosUsers = $em->getRepository('ListsArticleBundle:NewsFosUser')->findBy(array(
            'news' => $article,
            'user' => $user
        ));

        foreach ($newsFosUsers as $nfu) {
            $nfu->setViewed(new \DateTime(date('Y-m-d H:i:s')));
            $em->persist($nfu);
        }

        $em->flush();

        return $this->redirect($this->generateUrl('list_article_blog'));
    }

    /**
     * Last unviewed article
     * 
     * @return JSON
     */
    public function getLastNewAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newNFUs = $em->getRepository('ListsArticleBundle:NewsFosUser')->findBy(array(
            'user' => $this->getUser(),
            'viewed' => null
        ));
        $articles = [];
        foreach ($newNFUs as $nfu) {
            $article = $nfu->getNews();
            if (! in_array($article, $articles)) {
                $articles[] = $article;
            }
        }
        usort($articles, array(
            "Lists\ArticleBundle\Controller\ProdBlogController",
            "mysort1"
        ));
        if (isset($articles[0])) {
            $reports = [
                'title' => $articles[0]->getTitle(),
                'text' => $articles[0]->getText(),
                'id' => $articles[0]->getId()
            ];

            return new Response(json_encode($reports));
        } else {
            return new Response(null);
        }
    }

    /**
     * List of unviewed news
     * 
     * @return string
     */
    public function shortListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $newNFUs = $em->getRepository('ListsArticleBundle:NewsFosUser')->findBy(array(
            'user' => $this->getUser(),
            'viewed' => null
        ));
        $articles = [];
        foreach ($newNFUs as $nfu) {
            $article = $nfu->getNews();
            if (! in_array($article, $articles)) {
                $articles[] = $article;
            }
        }
        usort($articles, array(
            "Lists\ArticleBundle\Controller\ProdBlogController",
            "mysort1"
        ));

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':shortList.html.twig', array(
            'items' => $articles
        ));
    }

    /**
     * Sorting for shortListAction()
     * 
     * @param Article $a            
     * @param Article $b            
     *
     * @return integer
     */
    private function mysort1($a, $b)
    {
        $a = $a->getDatePublick();
        $b = $b->getDatePublick();
        if ($a == $b) {
            return 0;
        } else {
            return ($a > $b) ? - 1 : 1;
        }
    }

    /**
     * Adds new article into blog
     * 
     * @param Request $request            
     *
     * @return string
     */
    public function addAction(Request $request)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('article' . ucfirst($this->articleType) . 'Form');
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $party = $form->getData();
                $party->setUser($this->getUser());
                $party->setType($this->articleType);
                if (method_exists($party, 'getDatePublick') && $party->getDatePublick() != '') {
                    $party->setDatePublick(new \DateTime($party->getDatePublick()));
                }
                $party->setDateCreate(new \DateTime(date('Y-m-d H:i:s')));
                $em->persist($party);

                $part = $request->request->get($form->getName());
                if (isset($part['roles'])) {
                    $_roles = $part['roles'];
                    foreach ($_roles as $role) {
                        $fetchedRole = $em->getRepository('SDUserBundle:Group')->find($role);
                        $roles[] = $fetchedRole;
                    }
                } else {
                    $roles = $em->getRepository('SDUserBundle:Group')->findAll();
                }

                foreach ($roles as $role) {
                    $newsRole = new NewsRole();
                    $newsRole->setNews($party);
                    $newsRole->setRoles($role);
                    if (isset($part['vote'])) {
                        $newsRole->setVote($part['vote']);
                    }

                    $fetchedUsers = $role->getUsers()->toArray();

                    foreach ($fetchedUsers as $user) {
                        $nfu = new NewsFosUser();
                        $nfu->setNews($newsRole->getNews());
                        $nfu->setUser($user);
                        $em->persist($nfu);
                    }
                    $em->persist($newsRole);
                }
                $em->flush();
            } catch (\Exception $e) {
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('list_article_blog'));
        }

        return $this->render('ListsArticleBundle:' . $this->baseTemplate . ':add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Saves uploaded image
     * 
     * @param Request $request            
     *
     * @return string path to image
     */
    public function uploadAction(Request $request)
    {
        $name = $this->randomString();
        $ext = explode('.', $_FILES['file']['name']);
        $directory = $this->container->getParameter('project.web.dir');
        $directory .= '/uploads/blogimages/';
        if (! is_dir($directory)) {
            mkdir($directory, 0777);
        }
        $ext = explode('.', $_FILES['file']['name']);
        $filename = $name . '.' . $ext[1];
        $destination = $directory . $filename;
        $location = $_FILES["file"]["tmp_name"];
        move_uploaded_file($location, $destination);
        $directory = $this->container->getParameter('blogfiles.file.path');
        $destination = $directory . $filename;

        return new Response($destination);
    }

    /**
     * Random string
     *
     * @return string
     */
    private function randomString()
    {
        return md5(rand(100, 200));
    }
}
