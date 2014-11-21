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
    protected $filterCoworkerNamespace = 'oper.statistic.coworker';

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction()
    {

        // total statistic
        $info = $this->getStatisticInfo();

        return $this->render('ITDoorsOperBundle:Statistic:index.html.twig', $info);
    }

    public function filterPageAction()
    {


        return $this->render('ITDoorsOperBundle:Statistic:filterPage.html.twig', array());
    }

    public function coworkerPageAction()
    {

        return $this->render('ITDoorsOperBundle:Statistic:coworkerPage.html.twig', array());
    }

    /**
     * @return array
     */
    public function getOperManagerAjaxAction()
    {
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


    public function renderFilterResultAction()
    {
        $filterService = $this->get('itd.base_filter_service');
        $filters = $filterService->getFilters($this->filterNamespace);

        $info = $this->getStatisticInfo($filters);

        //var_dump($filters);
        $html = $this->renderView('ITDoorsOperBundle:Statistic:componentsTogether.html.twig', $info);
        return new Response($html);
    }

    public function renderFilterCoworkerResultAction()
    {
        $filterService = $this->get('itd.base_filter_service');
        $filters = $filterService->getFilters($this->filterCoworkerNamespace);


        $info = $this->getStatisticCoworkerInfo($filters);

        $html = $this->renderView('ITDoorsOperBundle:Statistic:coworkerGraph.html.twig', $info);
        return new Response($html);
    }


    private function getStatisticInfo($filters = null)
    {

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
        $daysCount++;
        if ($daysCount < 0) {
            $averagePerDay = 0;
        } else {
            $averagePerDay = $count / $daysCount;
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
                    //changing average due to filter
                    if ($numDays+1 < 0) {
                        $averagePerDay = 0;
                    } else {
                        $averagePerDay = $count / ($numDays + 1);
                    }
                }
            }
        }

        $filtersOther = $filters;
        $filtersOther['type'] = 'other';
        $filtersOnce = $filters;
        $filtersOnce['type'] = 'once';

        for ($i = $numDays; $i >= 0; $i--) {

            $date = clone($toDayGraph);
            $date->sub(new \DateInterval('P' . $i . 'D'));

            $numVisits = $statisticRepo->getStatistic($date, $filters);

            $numVisitsOther = $statisticRepo->getStatistic($date, $filtersOther);

            $numVisitsOnce = $statisticRepo->getStatistic($date, $filtersOnce);

            $key = $numDays - $i;
            $graph[$key]['year'] = $date->format('d-m');
            $graph[$key]['visits'] = $numVisits;

            $graph[$key]['visitsOther'] = $numVisitsOther;
            $graph[$key]['visitsOnce'] = $numVisitsOnce;

        }
        $graph = json_encode($graph);


        return array(
            'graph' => $graph,
            'totalVisits' => $totalVisits,
            'totalVisitsCommented' => $totalVisitsCommented,
            'averagePerDay' => $averagePerDay
        );
    }

    private function getStatisticCoworkerInfo($filters = null)
    {
        $statisticRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        $operManagers = $this->opermanagerList();
        $graph = array();
        $counter = 0;

        $filtersOther = $filters;
        $filtersOther['type'] = 'other';
        $filtersOnce = $filters;
        $filtersOnce['type'] = 'once';

        foreach ($operManagers as $operManager) {
            if (isset($filters['user']) && $filters['user']) {
                $users = explode(',', $filters['user']);
                if (!in_array($operManager['id'], $users)) {
                    continue;
                }
            }

            $visits = $statisticRepo->getCoworkerStatistic($operManager, $filters);
            $visitsOnce = $statisticRepo->getCoworkerStatistic($operManager, $filtersOnce);
            $visitsOther = $statisticRepo->getCoworkerStatistic($operManager, $filtersOther);


            $graph[$counter]['user'] = $operManager['fullName'];
            $graph[$counter]['visits'] = $visits;
            $graph[$counter]['visitsOnce'] = $visitsOnce;
            $graph[$counter]['visitsOther'] = $visitsOther;

            $counter++;
        }


        usort($graph, function ($item1,$item2)
        {
            if ($item1['visits'] == $item2['visits']) return 0;
            return ($item1['visits'] < $item2['visits']) ? 1 : -1;
        });

        $numElements = $counter;
        $graph = json_encode($graph);

        return array(
            'graph' => $graph,
            'numElements' => $numElements
        );

    }


    /**
     * opermanagerList
     *
     * @return Response
     */
    private function opermanagerList()
    {
        $return = array();

        $usersOper = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->findByRole('ROLE_OPER');

        array_walk($usersOper, function ($userOper, $key) use (&$return) {
            if ($userOper->getStuff()) {
                $return[] = array('id' =>  $userOper->getId(), 'fullName' => $userOper->getFullName());
            }
        });

        return $return;
    }


    public function exportReportAction() {
        $statisticRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:CommentOrganizer');

        $reports = $statisticRepo->getReports();

        $serviceExport = $this->get('itdoors_common.export.service');

        $excelObject = $serviceExport->getExcel($reports, 'ITDoorsOperBundle');



        $response = $serviceExport->getResponse($excelObject, 'Reports');

        return $response;
    }
}
