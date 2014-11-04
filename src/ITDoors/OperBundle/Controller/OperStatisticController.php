<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\OperBundle\Entity\CommentOrganizer;
use ITDoors\OperBundle\Entity\OperOrganizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * OperStatisticController class
 *
 * Default controller for oper page
 */
class OperStatisticController extends Controller
{

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction ()
    {

        $statisticRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');



        $graph = array();
        $numDays = 21;
        for ($i = $numDays; $i>=0; $i--) {

            $date = new \DateTime();
            $date->sub(new \DateInterval('P'.$i.'D'));

            $numVisits = $statisticRepo->getStatistic($date);

            $key = $numDays - $i;
            $graph[$key]['year'] = $date->format('Y-m-d');
            $graph[$key]['visits'] = $numVisits;
            //$test[$i]['expenses'] = 5;
        }
        $graph = json_encode($graph);

        $totalVisits = $statisticRepo->getTotalVisits();
        $totalVisitsCommented = $statisticRepo->getTotalVisitsCommented();


        return $this->render('ITDoorsOperBundle:Statistic:index.html.twig', array (
            'test' => $graph,
            'totalVisits' => $totalVisits,
            'totalVisitsCommented' => $totalVisitsCommented
        ));
    }

}
