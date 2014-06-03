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
        $monthName = date("F", strtotime($year.'-'.$month));
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



        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $query = $monthInfoRepository->getFilteredCoworkers($idDepartment, $month, $year, $filters);

        $coworkers = $query->getResult();

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
                $status = 'ok';
                if ($infoDay['isVacation']) {
                    $status = 'vacation';
                }
                if ($infoDay['isSkip']) {
                    $status = 'skip';
                }
                if ($infoDay['isFired']) {
                    $status = 'fired';
                }
                if ($infoDay['isSick']) {
                    $status = 'sick';
                }

                $infoHours[$idCoworker][$infoDay['day']]['status'] = $status;
                $infoHours[$idCoworker][$infoDay['day']]['officially'] = $infoDay['total'];
                $infoHours[$idCoworker][$infoDay['day']]['notOfficially'] = $infoDay['totalNotOfficially'];
            }

        }
        //ini_set('memory_limit', '512M');
        //var_dump(count($coworkers), count($dateInfo));
        //exit;
        return $this->render('ITDoorsOperBundle:Schedule:scheduleTable.html.twig', array(
            'days'=> $days,
            'coworkers' => $coworkers,
            'dateInfo' => $dateInfo,
            'idDepartment' => $idDepartment,
            'infoHours' => $infoHours,
            'year' => $year,
            'monthName' =>$monthName
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

        $monthName = date("F", strtotime($date));
        $dayName = date('D', strtotime($date));

        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleDay.html.twig', array(
            'coworkerDayTime' => $coworkerDayTime,
            'date' => $date,
            'idCoworker' => $idCoworker,
            'idDepartment' => $idDepartment,
            'monthName' => $monthName,
            'dayName' => $dayName,
            'day' => $day,
            'year' => $year
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
        $idTimeGrafik = $request->request->get('idTimeGrafik');

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

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        if ($grafikRepository->isCoworkerFired($idCoworker, $idDepartment)) {
            $return['success'] = 0;
            $return['error'] = 'fired';

            return new Response(json_encode($return));
        }

        list($hoursFromTime, $minutesFromTime) = explode(':', $fromTime);
        list($hoursToTime, $minutesToTime) = explode(':', $toTime);
        $midnight = '00:00';

        $timeIn = array();
        $infoDay['date'] = $date;
        $infoDay['from'] = $fromTime;
        $infoDay['to'] = $toTime;
        if (($hoursFromTime > $hoursToTime && $hoursToTime != 0) ||
            ($hoursToTime == 0 && $minutesToTime != 0 && $hoursFromTime != 0) ||
            ($hoursToTime == $hoursFromTime && $minutesFromTime>$minutesToTime) ||
            ($fromTime == $toTime)) {

            $return['success'] = 0;
            $return['error'] = 'wrong_from_to_time';

            return new Response(json_encode($return));


        }

/*            if ($hoursFromTime > $hoursToTime || ($hoursToTime == 0 && $minutesToTime != 0 && $hoursFromTime != 0)) {
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
            }*/


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
        //foreach ($timeIn as $infoDay) {
            list($year, $month, $day) = explode('-', $infoDay['date']);

        if (isset($idTimeGrafik) && $idTimeGrafik > 0) {
            $newTime = $this->getDoctrine()
                ->getRepository('ListsGrafikBundle:GrafikTime')
                ->find($idTimeGrafik);
        } else {
            $newTime = new GrafikTime();
        }

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

            if ($check1 < 0) {
                $check1 = 0;
            }
            if ($check2 < 0) {
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
    //}

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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteTimeFromDayAction(Request $request)
    {

        $return = array();
        $date =  $request->request->get('date');
        $idCoworker = $request->request->get('idCoworker');
        $idDepartment = $request->request->get('idDepartment');
        $idGrafikTime = $request->request->get('idGrafikTime');

        list($year, $month, $day) = explode('-', $date);

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');

        /** @var $grafik\Lists\GrafikBundle\Entity\Grafik   */
        $grafik = $grafikTimeRepository->find($idGrafikTime);

        if (!$grafik) {
            $return = array();
            $return['success'] = 0;
            $return['error'] = 'no_entity_found';

            return new Response(json_encode($return));
        }

        $em =  $this->getDoctrine()->getManager();
        $em->remove($grafik);
        $em->flush();

        $this->updateSumGrafik($day, $month, $year, $idCoworker, $idDepartment);

        $return['success'] = 1;

        return new Response(json_encode($return));

    }

    /**
     * @param integer $day
     * @param integer $month
     * @param integer $year
     * @param integer $idCoworker
     * @param integer $idDepartment
     */
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

        if (!$grafik) {
            $grafik = new Grafik();
            $grafik->setDay($day);
            $grafik->setMonth($month);
            $grafik->setYear($year);
            $grafik->setDepartmentPeople($departmentPeople);
            $grafik->setDepartment($department);
            $grafik->setReplacementType('r');
            $grafik->setDepartmentId($idDepartment);
            $grafik->setDepartmentPeopleId($idCoworker);
            $grafik->setDepartmentPeopleReplacement($departmentPeopleReplacement);
            $grafik->setDepartmentPeopleReplacementId(0);
        }

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

    /**
     * @param Request $request
     * 
     * @return Response
     */
    public function oneDayTotalAction(Request $request)
    {

        $params =  $request->request->get('params');

        $date = $params['date'];
        $idCoworker = $params['idCoworker'];
        $idDepartment = $params['idDepartment'];
        list($year, $month, $day) = explode('-', $date);

        $return = array();

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');


        $infoDay = $grafikRepository->getCoworkerHoursDayInfo(
            $day,
            $year,
            $month,
            $idDepartment,
            $idCoworker
        );

        if (!$infoDay[0]) {
            $officially = 0;
            $notOfficially = 0;
        } else {
            $officially = $infoDay[0]['total'];
            $notOfficially = $infoDay[0]['totalNotOfficially'];
        }

        $status = 'ok';
        if ($infoDay[0]['isVacation']) {
            $status = 'vacation';
        }
        if ($infoDay[0]['isSkip']) {
            $status = 'skip';
        }
        if ($infoDay[0]['isFired']) {
            $status = 'fired';
        }
        if ($infoDay[0]['isSick']) {
            $status = 'sick';
        }

        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleTableCell.html.twig', array(
            'officially'=> $officially,
            'notOfficially'=> $notOfficially,
            'status' => $status,
        ));

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function updateStatusCoworkerAction (Request $request)
    {
        $status = $request->request->get('status');
        $date =  $request->request->get('date');
        $idCoworker = $request->request->get('idCoworker');
        $idDepartment = $request->request->get('idDepartment');



        list($year, $month, $day) = explode('-', $date);

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

        if ($grafik) {
            if ($status == 'fired') {
                $grafik->setIsFired(true);
            }
            if ($status == 'vacation') {
                $grafik->setIsVacation(true);
            }
            if ($status == 'skip') {
                $grafik->setIsSkip(true);
            }
            if ($status == 'sick') {
                $grafik->setIsSick(true);
            }
            if ($status == 'ok') {
                $grafik->setIsSkip(false);
                $grafik->setIsSick(false);
                $grafik->setIsVacation(false);
                $grafik->setIsFired(false);
            }
            $em =  $this->getDoctrine()->getManager();
            $em->persist($grafik);
            $em->flush();
        }

        $return['success'] = 1;

        return new Response(json_encode($return));
    }
}
