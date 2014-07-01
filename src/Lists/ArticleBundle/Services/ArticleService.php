<?php

namespace Lists\ArticleBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Response;

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

        /** @var ArticleRepository $article */
        $articles = $em->getRepository('ListsArticleBundle:Vote')
            ->getVoitesFor15($id);

        $party =  $em->getRepository('ListsArticleBundle:Article')
            ->find($id);
        
        $emailTo = $this->container->getParameter('email.from');
        $nameTo = $this->container->getParameter('name.from');
        
        $email = $this->get('it_doors_email.service');
        
        foreach ($articles as $article) {
            echo $article['firstName'] . "\t\n";

            $email->send(
                array($emailTo => $nameTo), 'decision-making', array(
                'users' => array(
                    $article['email']
                ),
                'variables' => array(
                    '${lastName}$' => $article['lastName'],
                    '${firstName}$' => $article['firstName'],
                    '${middleName}$' => $article['middleName'],
                    '${id}$' =>
                    '<a href="' . $this->generateUrl(
                        'list_article_vote_decision_show', array('id' => $id), true
                    )
                    . '">' . $id . '</a>',
                    '${dateUnpublic}$' => date('d.m.Y H:i', $party->getDateUnpublick()->getTimestamp()),
                )
                )
            );
        }
    }
}
