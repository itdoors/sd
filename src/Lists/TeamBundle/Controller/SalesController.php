<?php

namespace Lists\TeamBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SalesController extends Controller
{
    protected $baseRoute = 'lists_sales_team_index';
    protected $baseRoutePrefix = 'sales';
    protected $baseTemplate = 'Sales';

    public function indexAction()
    {
        $user = $this->getUser();

        $myTeamsQuery = $this->getDoctrine()->getRepository('ListsTeamBundle:Team')
            ->getMyTeamsQuery($user->getId());

        $myTeams = $myTeamsQuery->getQuery()->getResult();

        return $this->render('ListsTeamBundle:' . $this->baseTemplate .':index.html.twig', array(
            'myTeams' => $myTeams,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseRoute' => $this->baseRoute
        ));
    }
}
