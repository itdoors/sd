<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\GrafikBundle\Entity\GrafikTime;
use Lists\GrafikBundle\Entity\Grafik;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * OperScheduleController class
 *
 * Default controller for oper schedule
 */
class OperScheduleController extends BaseFilterController
{

    protected $filterNamespace = 'ajax.namespace.oper.department.schedule';

    /**
     * indexAction
     *
     * @param integer $id
     *
     * @return mixed[]
     */

    public function indexAction($id)
    {

        $this->addToSessionValues('idDepartment', $id, 'param', 'oper.bundle.department', 'param');

        return $this->render('ITDoorsOperBundle:Schedule:schedule.html.twig', array(
            'id' => $id
        ));
    }

    /**
     * indexAction
     *
     * @param integer $id
     *
     * @return mixed[]
     */
    public function scheduleTableAction($id)
    {
        $idDepartment = $id;

        $holiday[] = '2014-01-01'; //New Year
        $holiday[] = '2014-01-07'; //Christmas
        $holiday[] = '2014-03-08'; //Jenskiy Den
        $holiday[] = '2014-04-20'; //Paska
        $holiday[] = '2014-05-01'; //Mayskie
        $holiday[] = '2014-05-02'; //Mayskie
        $holiday[] = '2014-05-09'; //Den Pobedy
        $holiday[] = '2014-06-08'; //Trinity
        $holiday[] = '2014-06-28'; //Den Konstituciy
        $holiday[] = '2014-08-24'; //Den Nezalejnosti

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);

        $year = intval(date('Y'));
        $month = intval(date('m'));

        if (isset($filters['year']) && $filters['year']) {
            $year = $filters['year'];
        }
        if (isset($filters['month']) && $filters['month']) {
            $month = $filters['month'];
        }

        $days = date("t", strtotime($year.'-'.$month)); //num days in selected month
        $dateInfo = array();

        $monthShow = $month;
        if ($month<10) {
            $monthShow = '0'.$month;
        }

        for ($i=0; $i<$days; $i++) {
            $day = $i+1;
            $dayShow = $day;
            if ($day<10) {
                $dayShow = '0'.$day;
            }
            $dateInfo[$i]['date'] = $year.'-'.$monthShow.'-'.$dayShow;
            $dateInfo[$i]['day'] = $day;
            $stingDate = date('D', strtotime($dateInfo[$i]['date']));
            $dateInfo[$i]['vacation'] = false;
            $dateInfo[$i]['dayName'] = $stingDate;
            if ($stingDate == 'Sat' || $stingDate == 'Sun') {
                $dateInfo[$i]['vacation'] = true;
            }
        }

        foreach ($dateInfo as $key => $dateValue) {
            if (in_array($dateValue['date'], $holiday)) {
                if (!$dateValue['vacation']) {
                    $dateInfo[$key]['vacation'] = true;

                } else {
                    if (!$dateInfo[$key+1]['vacation']) {
                        $dateInfo[$key+1]['vacation'] = true;
                    } else {
                        $dateInfo[$key+2]['vacation'] = true;
                    }
                }
            }
        }

        /** @var $departmentPeopleRepository \Lists\DepartmentBundle\Entity\DepartmentPeopleRepository   */
        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $query = $departmentPeopleRepository->getFilteredDepartmentPeopleQuery($id, $filters);
        $coworkers = $query->getResult();

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        $infoHours = array();
        /** @var $coworker \Lists\DepartmentBundle\Entity\DepartmentPeople   */
        foreach ($coworkers as $coworker) {

            $idCoworker = $coworker['id'];

            $infoHoursCoworker = $grafikRepository->getCoworkerHoursMonthInfo(
                $year,
                $month,
                $idDepartment,
                $idCoworker
            );

            //$info = array_fill(0, $days, '');

            foreach ($infoHoursCoworker as $infoDay) {
                $infoHours[$idCoworker][$infoDay['day']]['officially'] = $infoDay['total'];
                $infoHours[$idCoworker][$infoDay['day']]['notOfficially'] = $infoDay['totalNotOfficially'];
            }

        }

        return $this->render('ITDoorsOperBundle:Schedule:scheduleTable.html.twig', array(
            'days'=> $days,
            'coworkers' => $coworkers,
            'dateInfo' => $dateInfo,
            'idDepartment' => $idDepartment,
            'infoHours' => $infoHours
        ));
    }

    /**
     * getting the info about grafik for one day for exact user
     *
     * @param Request $request
     *
     * @return string
     */
    public function oneDayInfoAction(Request $request)
    {
        $date =  $request->request->get('date');
        $idCoworker = $request->request->get('idCoworker');
        $idDepartment = $request->request->get('idDepartment');

/*        if (!isset($date) || !isset($idCoworker) || !isset($idDepartment)) {
            echo ('some error, variables had not been sent');exit;
        }*/
        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');
        $dateParts = explode('-', $date);

        $day = $dateParts[2];
        $month = $dateParts[1];
        $year = $dateParts[0];

        $coworkerDayTime = $grafikTimeRepository->getCoworkerHoursDayInfo(
            $year,
            $month,
            $day,
            $idDepartment,
            $idCoworker
        );

        //var_dump($coworkerDayTime[0]);
        $return = array();

        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleDay.html.twig', array(
            'coworkerDayTime' => $coworkerDayTime,
            'date' => $date,
            'idCoworker' => $idCoworker,
            'idDepartment' => $idDepartment
        ));
        $return['success'] = 1;

        return new Response(json_encode($return));

    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addNewTimeAction(Request $request)
    {
        $return = array();
        $return['html'] = '';
        $date =  $request->request->get('date');
        $idCoworker = $request->request->get('idCoworker');
        $idDepartment = $request->request->get('idDepartment');
        $officially = $request->request->get('officially');
        $fromTime = $request->request->get('fromTime');
        $toTime = $request->request->get('toTime');

        $addTypeOfficially = '';

        if ($officially == 'false') {
            $officially = false;
            //we will need this down te code
            $addTypeOfficially = 'NotOfficially';
        } else {
            $officially = true;
        }

        $departmentPeople  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find($idCoworker);

        //can't non-official person work officially
        if ($departmentPeople->getAdmissionDate() == null && $officially) {
            $return['success'] = 0;
            $return['error'] = 'no_official_permitted';

            return new Response(json_encode($return));
        }

        /* if ($departmentPeople->getFaired()) {
             $return['success'] = 0;
             $return['error'] = 'faired';
         }*/

        list($hoursFromTime, $minutesFromTime) = explode(':', $fromTime);
        list($hoursToTime, $minutesToTime) = explode(':', $toTime);
        $midnight = '00:00';

        $timeIn = array();
        if ($fromTime != $toTime) {
            if ($hoursFromTime > $hoursToTime || ($hoursToTime == 0 && $minutesToTime != 0 && $hoursFromTime != 0)) {
                $timeIn[0]['from'] = $fromTime;
                $timeIn[0]['to'] = $midnight;
                $timeIn[0]['date'] = $date;

                $timeIn[1]['from'] = $midnight;
                $timeIn[1]['to'] = $toTime;
                //date of the next day
                $timeIn[1]['date'] = date('Y-m-d', strtotime($date .' +1 day'));
            } else {
                $timeIn[0]['from'] = $fromTime;
                $timeIn[0]['to'] = $toTime;
                $timeIn[0]['date'] = $date;
            }
        }

        $department  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->find($idDepartment);



        $departmentPeopleReplacement  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find(0);

        //array of points during the day which make periods of the day(evening, night, etc)
        $periodPoints[] = 7;
        $periodPoints[] = 19;
        $periodPoints[] = 22;
        $periodPoints[] = 24;
        foreach ($timeIn as $infoDay) {
            list($year, $month, $day) = explode('-', $infoDay['date']);

            $newTime = new GrafikTime();
            $newTime->setDay($day);
            $newTime->setYear($year);
            $newTime->setMonth($month);

            $newTime->setFromTime(new \DateTime($infoDay['from'].':00'));
            $newTime->setToTime(new \DateTime($infoDay['to'].':00'));

            list($hoursFrom, $minutesFrom) = explode(':', $infoDay['from']);
            list($hoursTo, $minutesTo) = explode(':', $infoDay['to']);
            if ($hoursTo == 0 && $hoursFrom != 0) {
                $hoursTo = 24;
            }
            $hoursFrom += $minutesFrom/60;
            $hoursTo += $minutesTo/60;

            //Algorithm to calculate number of hours
            //between two periods
            $totalPeriod = array();
            foreach ($periodPoints as $point) {
                $check1 = $point - $hoursFrom;
                $check2 = $point - $hoursTo;

                if ($check1<0) {
                    $check1 = 0;
                }
                if ($check2<0) {
                    $check2 = 0;
                }
                $hours = $check1 - $check2 - array_sum($totalPeriod);
                $totalPeriod[] = $hours;
            }
            //end of algorithm

            $totalNight = $totalPeriod[0] + $totalPeriod[3];//from 0-7, 22-24
            $totalEvening = $totalPeriod[2];//from 7-19
            $totalDay = $totalPeriod[1];//from 19-22

            $total = $totalNight + $totalEvening + $totalDay;

            $funcTotal = 'setTotal'.$addTypeOfficially;
            $funcTotalDay = 'setTotalDay'.$addTypeOfficially;
            $funcTotalEvening = 'setTotalEvening'.$addTypeOfficially;
            $funcTotalNight = 'setTotalNight'.$addTypeOfficially;

            //setting official or not total hours
            $newTime->$funcTotal($total);
            $newTime->$funcTotalDay($totalDay);
            $newTime->$funcTotalEvening($totalEvening);
            $newTime->$funcTotalNight($totalNight);
            $newTime->setNotOfficially(!$officially);
            $newTime->setDepartment($department);
            $newTime->setDepartmentPeople($departmentPeople);
            $newTime->setDepartmentPeopleReplacement($departmentPeopleReplacement);

            $em = $this->getDoctrine()->getManager();
            $em->persist($newTime);
            $em->flush();

            $return['html'] .= $this->renderView('ITDoorsOperBundle:Schedule:scheduleDayTableRow.html.twig', array(
                'oneDayTime'=> $newTime
            ));


            $this->updateSumGrafik($day, $month, $year, $idCoworker, $idDepartment);
        }

        return new Response(json_encode($return));
    }

    /**
     * Render one row of the table in schedule of one day
     *
     * @param mixed[] $oneDayTime
     *
     * @return Response
     */
    public function scheduleTableRowAction($oneDayTime)
    {
        return $this->render('ITDoorsOperBundle:Schedule:scheduleDayTableRow.html.twig', array(
            'oneDayTime'=> $oneDayTime
        ));
    }


    private function updateSumGrafik($day, $month, $year, $idCoworker, $idDepartment)
    {

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');


        $coworkerDayTimes = $grafikTimeRepository->getCoworkerHoursDayInfo(
            $year,
            $month,
            $day,
            $idDepartment,
            $idCoworker
        );
        $total = 0;
        $totalDay = 0;
        $totalEvening = 0;
        $totalNight = 0;

        $totalNotOfficially = 0;
        $totalDayNotOfficially= 0;
        $totalEveningNotOfficially = 0;
        $totalNightNotOfficially = 0;

        foreach ($coworkerDayTimes as $coworkerTime) {
            $total += $coworkerTime->getTotal();
            $totalDay += $coworkerTime->getTotalDay();
            $totalEvening += $coworkerTime->getTotalEvening();
            $totalNight += $coworkerTime->getTotalNight();

            $totalNotOfficially += $coworkerTime->getTotalNotOfficially();
            $totalDayNotOfficially += $coworkerTime->getTotalDayNotOfficially();
            $totalEveningNotOfficially += $coworkerTime->getTotalEveningNotOfficially();
            $totalNightNotOfficially += $coworkerTime->getTotalNightNotOfficially();
        }

        $department  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->find($idDepartment);

        $departmentPeople  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find($idCoworker);

        $departmentPeopleReplacement  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find(0);

        /** @var  $grafik \Lists\GrafikBundle\Entity\Grafik*/
        $grafik  =$this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik')
            ->findOneBy(array(
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'departmentPeople' => $departmentPeople,
                'department' => $department,
                'departmentPeopleReplacement' => $departmentPeopleReplacement,
                'replacementType' => 'r'
            ));

        $grafik->setTotal($total);
        $grafik->setTotalDay($totalDay);
        $grafik->setTotalEvening($totalEvening);
        $grafik->setTotalNight($totalNight);
        $grafik->setTotalNotOfficially($totalNotOfficially);
        $grafik->setTotalDayNotOfficially($totalDayNotOfficially);
        $grafik->setTotalEveningNotOfficially($totalEveningNotOfficially);
        $grafik->setTotalNightNotOfficially($totalNightNotOfficially);

        $em =  $this->getDoctrine()->getManager();
        $em->persist($grafik);
        $em->flush();
    }
}
