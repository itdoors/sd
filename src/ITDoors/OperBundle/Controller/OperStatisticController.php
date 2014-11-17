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
    protected $filterNamespace = 'oper.statistic';

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction ()
    {

        // total statistic
        $info = $this->getStatisticInfo();

        return $this->render('ITDoorsOperBundle:Statistic:index.html.twig', $info);
    }

    public function filterPageAction() {



        return $this->render('ITDoorsOperBundle:Statistic:filterPage.html.twig', array (
        ));
    }

    /**
     * @return array
     */
    public function getOperManagerAjaxAction() {
        $searchText = $this->get('request')->query->get('query');

        /** @var \SD\UserBundle\Entity\UserRepository $repository */
        $repository = $this->container->get('sd_user.repository');

        $objects = $repository->getOnlyStuff()
            ->leftJoin('u.groups', 'g')
            ->andWhere('g.name = :operRole')
            ->setParameter(':operRole', 'OPER')
            ->andWhere('lower(u.firstName) LIKE :q OR lower(u.lastName) LIKE :q')
            ->setParameter(':q', mb_strtolower($searchText, 'UTF-8') . '%')
            ->getQuery()
            ->getResult();

        $result = array();

        foreach ($objects as $object) {

            $result[] = array(
                'id' => $object->getId(),
                'value' => $object->getId(),
                'name' => (string)$object,
                'text' => (string)$object
            );
        }

        return new Response(json_encode($result));
    }


    public function renderFilterResultAction() {
        $filterService = $this->get('itd.base_filter_service');
        $filters = $filterService->getFilters($this->filterNamespace);

        $info = $this->getStatisticInfo($filters);

        //var_dump($filters);
        $html = $this->renderView('ITDoorsOperBundle:Statistic:componentsTogether.html.twig', $info);
        return new Response($html);
    }


    private function getStatisticInfo($filters = null) {

        $statisticRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        //total and total commented
        $totalVisits = $statisticRepo->getTotalVisits($filters);
        $totalVisitsCommented = $statisticRepo->getTotalVisitsCommented($filters);

        // avarage visits per day counting
        $averageInfo = $statisticRepo->getAveragePerDayVisits($filters);
        $count = $averageInfo['countAll'];
        $minDate = new \DateTime($averageInfo['minDate']);
        $maxDate = new \DateTime($averageInfo['maxDate']);

        $interval = $maxDate->diff($minDate);
        $daysCount = $interval->format('%a');

        if (!$daysCount) {
            $averagePerDay = 0;
        } else {
            $averagePerDay = $count/$daysCount;
        }

        //graph part
        $graph = array();
        $numDays = 30; // number of days in graph
        $toDayGraph = new \DateTime();

        if ($filters) {
            if (isset($filters['daterange']) && $filters['daterange']) {
                if ($filters['daterange']['start'] || $filters['daterange']['end']) {
                    $start = $filters['daterange']['start'];
                    $end = $filters['daterange']['end'];
                    $intervalGraph = $end->diff($start);
                    $numDays = $intervalGraph->format('%a');
                    $toDayGraph = $end;
                }
            }
        }
        for ($i = $numDays; $i>=0; $i--) {

            $date = clone($toDayGraph);
            $date->sub(new \DateInterval('P'.$i.'D'));

            $numVisits = $statisticRepo->getStatistic($date, $filters);

            $key = $numDays - $i;
            $graph[$key]['year'] = $date->format('d-m');
            $graph[$key]['visits'] = $numVisits;
            //$test[$i]['expenses'] = 5;
        }
        $graph = json_encode($graph);


        return array (
            'graph' => $graph,
            'totalVisits' => $totalVisits,
            'totalVisitsCommented' => $totalVisitsCommented,
            'averagePerDay' => $averagePerDay
        );
    }
}
