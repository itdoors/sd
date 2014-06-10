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
            'month' => $month,
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
        $idLink = $request->request->get('idLink');
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
            'year' => $year,
            'idLink' => $idLink
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

        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        //check for errors of admission/dismissal dates
        $returnCheck = $this->checkErrorsForChangingDate($idCoworker, $date, $officially);
        if ($returnCheck['success'] == 0) {
            return new Response(json_encode($returnCheck));
        }

        /** @var  $departmentPeople \Lists\DepartmentBundle\Entity\DepartmentPeople */
        $departmentPeople = $departmentPeopleRepository->find($idCoworker);

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        //////////////////
        list($hoursFromTime, $minutesFromTime) = explode(':', $fromTime);
        list($hoursToTime, $minutesToTime) = explode(':', $toTime);
        $midnight = '00:00';

        $timeIn = array();
        $infoDay['date'] = $date;
        $infoDay['from'] = $fromTime;
        $infoDay['to'] = $toTime;

        //wrong time error
        if (($hoursFromTime > $hoursToTime && $hoursToTime != 0) ||
            ($hoursToTime == 0 && $minutesToTime != 0 && $hoursFromTime != 0) ||
            ($hoursToTime == $hoursFromTime && $minutesFromTime>$minutesToTime) ||
            ($fromTime == $toTime)) {

            $return['success'] = 0;
            $return['error'] = 'wrong_from_to_time';

            return new Response(json_encode($return));
        }

        //check if time does not have sub times already in db

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');
        list($year, $month, $day) = explode('-', $date);

        $havingSubtime = $grafikTimeRepository->havingSubtime(
            $year,
            $month,
            $day,
            $idDepartment,
            $idCoworker,
            $fromTime,
            $toTime,
            $idTimeGrafik
        );
        if ($havingSubtime) {
            $return['success'] = 0;
            $return['error'] = 'subtime_having';
            $return['info'] = $havingSubtime;

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

        if ($addTypeOfficially == '') {
            $cleanOtherOfficially = 'NotOfficially';
        } else {
            $cleanOtherOfficially = '';
        }
        $funcTotalClean = 'setTotal'.$cleanOtherOfficially;
        $funcTotalDayClean = 'setTotalDay'.$cleanOtherOfficially;
        $funcTotalEveningClean = 'setTotalEvening'.$cleanOtherOfficially;
        $funcTotalNightClean = 'setTotalNight'.$cleanOtherOfficially;

        $newTime->$funcTotalClean(0);
        $newTime->$funcTotalDayClean(0);
        $newTime->$funcTotalEveningClean(0);
        $newTime->$funcTotalNightClean(0);


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

        if (!isset($infoDay[0])) {
            $officially = 0;
            $notOfficially = 0;
            $status = 'ok';
        } else {
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

        var_dump($status);
        var_dump($date);
        var_dump($idCoworker);
        var_dump($idDepartment);

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

        if (!$grafik) {
            $grafik = new \Lists\GrafikBundle\Entity\Grafik();

            $grafik->setDay($day);
            $grafik->setMonth($month);
            $grafik->setYear($year);
            $grafik->setDepartmentPeople($departmentPeople);
            $grafik->setDepartment($department);
            $grafik->setDepartmentPeopleReplacement($departmentPeopleReplacement);
            $grafik->setReplacementType('r');
            $grafik->setDepartmentId($idDepartment);
            $grafik->setDepartmentPeopleId($idCoworker);
            $grafik->setDepartmentPeopleReplacementId(0);
        }

        $grafik->setIsSkip(false);
        $grafik->setIsSick(false);
        $grafik->setIsVacation(false);
        $grafik->setIsFired(false);

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
        $em =  $this->getDoctrine()->getManager();
        $em->persist($grafik);
        $em->flush();


        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function copyMonthDatesAction(Request $request)
    {
        $dates =  $request->request->get('dates');
        $idCoworker = $request->request->get('idCoworker');
        $idDepartment = $request->request->get('idDepartment');
        $currentDate = $request->request->get('currentDate');

        list($year, $month, $day) = explode('-', $currentDate);

        foreach ($dates as $key => $date) {
            $dates[$key] = date('d', strtotime($date));
        }
        $return = array();

        $key = array_search($day, $dates);
        if ($key !== false) {
            unset($dates[$key]);
        }

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');



        $copyGrafikTimes = $grafikTimeRepository->findBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $day,
            'year' => $year,
            'month' => $month
        ));

        $hasOfficially = false;
        $hasNotOfficially = false;
        /** @var  $copyGrafikTime \Lists\GrafikBundle\Entity\GrafikTime */
        foreach ($copyGrafikTimes as $copyGrafikTime) {
            if (!$copyGrafikTime->getNotOfficially()) {
                $hasOfficially = true;
            } else {
                $hasNotOfficially = true;
            }
        }

        $return['errors'] = 0;

        //check for wrong dates, like admission/dismissal not correct
        foreach ($dates as $dayCopy) {
            $date = $year.'-'.$month.'-'.$dayCopy;
            $checkOfficially['success'] = 1;
            $checkNotOfficially['success'] = 1;
            if ($hasNotOfficially) {
                $checkOfficially = $this->checkErrorsForChangingDate($idCoworker, $date, false);
            }
            if ($hasOfficially) {
                $checkNotOfficially = $this->checkErrorsForChangingDate($idCoworker, $date, true);
            }

            if ($checkOfficially['success'] == 0 || $checkNotOfficially['success'] == 0) {
                $return['errors'] = 1;
                $return['errorDay'][] = $dayCopy;
                //deleting from the copy array
                $key = array_search($dayCopy, $dates);
                if ($key !== false) {
                    unset($dates[$key]);
                }
            }
        }

        if (count($dates) == 0) {
            $return['success'] = 1;

            return new Response(json_encode($return));
        }

        //deleting old day grafik times

        $coworkerDayTimes = $grafikTimeRepository->findBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $dates,
            'year' => $year,
            'month' => $month
        ));

        $em =  $this->getDoctrine()->getManager();

        foreach ($coworkerDayTimes as $coworkerDayTime) {
            $em->remove($coworkerDayTime);
        }
        $em->flush();


        //deleting old day grafik
        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');
        $coworkerDayTimesTotal = $grafikRepository->findBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $dates,
            'year' => $year,
            'month' => $month
        ));
        foreach ($coworkerDayTimesTotal as $coworkerDayTimeTotal) {
            $em->remove($coworkerDayTimeTotal);
        }
        $em->flush();

        //copying new daytime to grafik time
        /** @var  $copyGrafikTime \Lists\GrafikBundle\Entity\GrafikTime */
        $copyGrafikTimes = $grafikTimeRepository->findBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $day,
            'year' => $year,
            'month' => $month
        ));

        foreach ($copyGrafikTimes as $copyGrafikTime) {
            foreach ($dates as $dayNew) {
                $cloneGrafikTime = clone $copyGrafikTime;
                $cloneGrafikTime->setDay($dayNew);
                $em->persist($cloneGrafikTime);
            }
        }
        $em->flush();

        //copying new daytime to grafik
        /** @var  $copyGrafikTime \Lists\GrafikBundle\Entity\Grafik */
        $copyGrafik = $grafikRepository->findOneBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $day,
            'year' => $year,
            'month' => $month
        ));
        if ($copyGrafik) {
            foreach ($dates as $dayNew) {
                $copyGrafik = clone $copyGrafik;
                $copyGrafik->setDay($dayNew);
                $em->persist($copyGrafik);
            }
        }
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param integer $idCoworker
     * @param string  $date
     * @param boolean $officially
     *
     * @return mixed
     */
    private function checkErrorsForChangingDate($idCoworker, $date, $officially)
    {

        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        /** @var  $departmentPeople \Lists\DepartmentBundle\Entity\DepartmentPeople */
        $departmentPeople = $departmentPeopleRepository->find($idCoworker);
        //can't person work when fired
        if ($departmentPeople->getDismissalDateNotOfficially() != null
            && $departmentPeople->getDismissalDateNotOfficially() < new \DateTime($date)) {

            $return['success'] = 0;
            $return['error'] = 'fired';

            return $return;

        }

        //can't non-official person work officially
        if ($departmentPeople->getAdmissionDate() == null && $officially) {
            $return['success'] = 0;
            $return['error'] = 'no_official_permitted';

            return $return;
        } elseif ($departmentPeople->getAdmissionDate() != null
            && $departmentPeople->getAdmissionDate() < new \DateTime($date)
            && $departmentPeople->getDismissalDate() != null
            && $departmentPeople->getDismissalDate() > new \DateTime($date)
            && $officially) {

            $return['success'] = 0;
            $return['error'] = 'no_official_permitted';

            return $return;
        } elseif ($departmentPeople->getAdmissionDate() != null
            && $departmentPeople->getAdmissionDate() > new \DateTime($date)) {

            $return['success'] = 0;
            $return['error'] = 'no_official_permitted';

            return $return;
        }

        $return['success'] = 1;

        return $return;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function copyCoworkersByEtalonAction(Request $request)
    {

        $idsCopy =  $request->request->get('ids');
        $idSelected = $request->request->get('selected');
        $idDepartment = $request->request->get('idDepartment');
        $date = $request->request->get('date');

        list($year, $month) = explode('-', $date);

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');


        //checking if possible to copy
        $return['errors'] = 0;

        $departmentPeopleRepository  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        foreach ($idsCopy as $idCopy) {
            for ($dayCopy=1; $dayCopy<=date("t", strtotime($year.'-'.$month)); $dayCopy++) {
                $copyGrafikTimes = $grafikTimeRepository->findBy(array(
                    'department' => $idDepartment,
                    'departmentPeople' => $idSelected,
                    'day' => $dayCopy,
                    'year' => $year,
                    'month' => $month
                ));

                $hasOfficially = false;
                $hasNotOfficially = false;

                /** @var  $copyGrafikTime \Lists\GrafikBundle\Entity\GrafikTime */
                foreach ($copyGrafikTimes as $copyGrafikTime) {
                    if (!$copyGrafikTime->getNotOfficially()) {
                        $hasOfficially = true;
                    } else {
                        $hasNotOfficially = true;
                    }
                }
                $date = $year.'-'.$month.'-'.$dayCopy;
                $checkOfficially['success'] = 1;
                $checkNotOfficially['success'] = 1;
                if ($hasNotOfficially) {
                    $checkOfficially = $this->checkErrorsForChangingDate($idCopy, $date, false);
                }
                if ($hasOfficially) {
                    $checkNotOfficially = $this->checkErrorsForChangingDate($idCopy, $date, true);
                }

                if ($checkOfficially['success'] == 0 || $checkNotOfficially['success'] == 0) {
                    $return['errors'] = 1;

                    $errorPerson = $departmentPeopleRepository->find($idCopy);
                    $return['errorName'][] = $errorPerson->getFirstName().' '.
                        $errorPerson->getMiddleName().' '.$errorPerson->getLastName();
                    //deleting from the copy array
                    $key = array_search($idCopy, $idsCopy);
                    if ($key !== false) {
                        unset($idsCopy[$key]);
                        break;
                    }
                }
            }
        }

        if (count($idsCopy) == 0) {
            $return['success'] = 1;

            return new Response(json_encode($return));
        }

        $em =  $this->getDoctrine()->getManager();


        foreach ($idsCopy as $idCopy) {
            $departmentPeople  = $this->getDoctrine()
                ->getRepository('ListsDepartmentBundle:DepartmentPeople')
                ->find($idCopy);

            //deleting old day grafik times
            $coworkerDayTimes = $grafikTimeRepository->findBy(array(
                'department' => $idDepartment,
                'departmentPeople' => $idCopy,
                'year' => $year,
                'month' => $month
            ));

            foreach ($coworkerDayTimes as $coworkerDayTime) {
                $em->remove($coworkerDayTime);
            }
            //deleting old day grafik
            $coworkerDayTimesTotal = $grafikRepository->findBy(array(
                'department' => $idDepartment,
                'departmentPeople' => $idCopy,
                'year' => $year,
                'month' => $month
            ));
            foreach ($coworkerDayTimesTotal as $coworkerDayTimeTotal) {
                $em->remove($coworkerDayTimeTotal);
            }
            $em->flush();

            //copying new daytime to grafik time
            /** @var  $copyGrafikTime \Lists\GrafikBundle\Entity\GrafikTime */
            $copyGrafikTimes = $grafikTimeRepository->findBy(array(
                'department' => $idDepartment,
                'departmentPeople' => $idSelected,
                'year' => $year,
                'month' => $month
            ));
            if (count($copyGrafikTimes) > 0) {
                foreach ($copyGrafikTimes as $copyGrafikTime) {
                        $cloneGrafikTime = clone $copyGrafikTime;
                        $cloneGrafikTime->setDepartmentPeople($departmentPeople);
                        $em->persist($cloneGrafikTime);
                }
                $em->flush();
            }

            //copying new daytime to grafik
            /** @var  $copyGrafik \Lists\GrafikBundle\Entity\Grafik */
            $copyGrafiks = $grafikRepository->findBy(array(
                'department' => $idDepartment,
                'departmentPeople' => $idSelected,
                'year' => $year,
                'month' => $month
            ));
            if (count($copyGrafiks) > 0) {
                foreach ($copyGrafiks as $copyGrafik) {
                        $cloneGrafikTime = clone $copyGrafik;
                        $cloneGrafikTime->setDepartmentPeople($departmentPeople);
                        $cloneGrafikTime->setDepartmentPeopleId($idCopy);
                        $em->persist($cloneGrafikTime);
                }
            }
            $em->flush();

        }

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showUserBasicTableAction(Request $request)
    {
        $params =  $request->request->get('params');
        $date = $request->request->get('date');

        $idCoworker =  $params['idCoworker'];
        $idDepartment = $params['idDepartment'];

        list($year, $month) = explode('-', $date);

        //$date = $request->request->get('date');

        $return['html'] = '';
        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');
        /** @var  $departmentPeople \Lists\DepartmentBundle\Entity\DepartmentPeople */
        $departmentPeople = $departmentPeopleRepository->find($idCoworker);

        $info['id'] = $departmentPeople->getId();
        $info['mpk'] = $departmentPeople->getMpks();
        $info['fio'] = $departmentPeople->getLastName().' '.
            $departmentPeople->getMiddleName().' '.$departmentPeople->getFirstName();
        $info['dateAcceptedOfficially'] = $departmentPeople->getAdmissionDate();
        $info['dateAcceptedNotOfficially'] = $departmentPeople->getAdmissionDateNotOfficially();
        $info['dateFiredOfficially'] = $departmentPeople->getDismissalDate();
        $info['dateFiredNotOfficially'] = $departmentPeople->getDismissalDateNotOfficially();

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array(
            'departmentPeople' => $departmentPeople->getId(),
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            'departmentPeopleReplacement' => 0

        ));



        $onceOnlyAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:OnceOnlyAccrual');

        $onceOnlyAccrual = $onceOnlyAccrualRepository->findBy(array(
            'departmentPeopleMonthInfo'=>$monthInfo->getId()
        ));


        $accrual['officially'] = array();
        $accrual['notOfficially'] = array();

                /** @var $oneAccrual \Lists\DepartmentBundle\Entity\OnceOnlyAccrual */
/*          foreach($onceOnlyAccrual as $oneAccrual) {
          if ($oneAccrual->getCode() == 'oz') {
              $type = 'officially';
          } else if ($oneAccrual->getCode() == 'uz') {
              $type = 'notOfficially';
          }

                  $accrual[$type]['id'] = $oneAccrual->getId();
                  $accrual[$type]['type'] = $oneAccrual->getType();
                  $accrual[$type]['workType'] = $oneAccrual->getWorkType();
                  $accrual[$type]['value'] = $oneAccrual->getValue();
                  $accrual[$type]['value'] = $oneAccrual->getValue();
              }

              $accrual['officially']['id'] = 'sdfsdf';
              $accrual['officially']['type'] = 'asdfsdf';
              $accrual['officially']['workType'] = 'fdgdfg';
              $accrual['officially']['value'] = 'asdfsd';
              $accrual['officially']['value'] = 'sdf';*/


          $plannedAccrualRepository = $this->getDoctrine()
              ->getRepository('ListsDepartmentBundle:PlannedAccrual');

          $plannedAccrual = $plannedAccrualRepository->findBy(array(
              'departmentPeople' => $idCoworker
          ));


          $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleInfoUserBasic.html.twig', array(
              'coworker'=> $info,
              'accrual' => $onceOnlyAccrual,
              'planned' => $plannedAccrual
          ));


          $return['success'] = 1;

          return new Response(json_encode($return));
    }

    /**
    * @param Request $request
    *
    * @return Response
    */
    public function insertAccrualAction(Request $request)
    {
        $date = $request->request->get('date');
        $idCoworker =  $request->request->get('idCoworker');
        $idDepartment = $request->request->get('idDepartment');
        $value = $request->request->get('value');
        $type = $request->request->get('type');
        $workType = $request->request->get('workType');
        $description = $request->request->get('description');
        $officially = $request->request->get('officially');
        $value = str_replace(',', '.', $value);

        list($year, $month) = explode('-', $date);

        $return['success'] = 0;
        if ($officially == 'true') {
            $check = true;
        } else {
            $check = false;
        }
        for ($i=1; $i<=date("t", strtotime($year.'-'.$month)); $i++) {
            $return = $this->checkErrorsForChangingDate($idCoworker, $date.'-01', $check);
            if ($return['success'] == 1) {
                break;
            }
        }

        if ($return['success'] == 0) {

            return new Response(json_encode($return));
        }


        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array(
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            'departmentPeopleReplacement' => 0
        ));

        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');
        /** @var  $departmentPeople \Lists\DepartmentBundle\Entity\DepartmentPeople */
        $departmentPeople = $departmentPeopleRepository->find($idCoworker);


        if ($officially == 'true') {
            $code = 'oz';
        } else {
            $code = 'uz';
        }

        $newAccrual = new \Lists\DepartmentBundle\Entity\OnceOnlyAccrual();
        $newAccrual->setCode($code);
        $newAccrual->setDescription($description);
        $newAccrual->setValue($value);
        $newAccrual->setMpk($departmentPeople->getMpks());
        $newAccrual->setWorkType($workType);
        $newAccrual->setType($type);
        $newAccrual->setIsActive(true);
        $newAccrual->setDepartmentPeopleMonthInfo($monthInfo);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newAccrual);
        $em->flush();

        $onceOnlyAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:OnceOnlyAccrual');

        $onceOnlyAccrual = $onceOnlyAccrualRepository->findBy(array(
            'departmentPeopleMonthInfo'=>$monthInfo->getId()
        ));

        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleInfoUserBasicAccrual.html.twig', array(
            'accruals'=> $onceOnlyAccrual,
            'code' => $code
        ));

        $return['success'] = 1;

        return new Response(json_encode($return));
    }


    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAccrualAction(Request $request)
    {
        $id = $request->request->get('pk');
        $date = $request->request->get('date');
        $idCoworker =  $request->request->get('idCoworker');
        $code = $request->request->get('code');

        list($year,$month) = explode('-', $date);

        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');
        /** @var  $departmentPeople \Lists\DepartmentBundle\Entity\DepartmentPeople */
        $departmentPeople = $departmentPeopleRepository->find($idCoworker);

        $onceOnlyAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:OnceOnlyAccrual');

        $deleteAccrual = $onceOnlyAccrualRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($deleteAccrual);
        $em->flush();

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array(
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            'departmentPeopleReplacement' => 0
        ));

        $onceOnlyAccrual = $onceOnlyAccrualRepository->findBy(array(
            'departmentPeopleMonthInfo' => $monthInfo->getId()
        ));

        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleInfoUserBasicAccrual.html.twig', array(
            'accruals'=> $onceOnlyAccrual,
            'code' => $code
        ));

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    public function addUserToGrafik()
    {

    }
}
