<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use ITDoors\OperBundle\Services\ScheduleService;
use Lists\DepartmentBundle\Entity\DepartmentPeopleMonthInfoRepository;
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
        $this->checkEmtyDateFilter();

        return $this->render('ITDoorsOperBundle:Schedule:schedule.html.twig', array(
            'id' => $id
        ));
    }

    /**
     * checkEmtyDateFilter
     */
    private function checkEmtyDateFilter()
    {
        $year = intval(date('Y'));
        $month = intval(date('m'));

        $filterNamespace = $this->container->getParameter($this->getNamespace());
        $filters = $this->getFilters($filterNamespace);

        if (!array_key_exists('year', $filters) || $filters['year'] == null || $filters['year'] == '') {
            $this->addToFilters('year', $year, $filterNamespace);
        }
        if (!array_key_exists('month', $filters) || $filters['month'] == null || $filters['month'] == '') {
            $this->addToFilters('month', $month, $filterNamespace);
        }
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
        if ($id == 0) {
            /** @var AccessService $accessService */
            $accessService = $this->get('access.service');
            $idDepartment = $accessService->getAllowedDepartmentsId();
        }
        if (is_array($idDepartment) || $idDepartment === false) {
            $departmentIsArray = true;
        } else {
            $departmentIsArray = false;
        }

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
        $monthDaysRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Salary');

        $monthDay = $monthDaysRepository->findOneBy(array(
            'year' => $year,
            'month' =>$month
        ));

        $holiday = array();

        $dayAdvance = $monthDay->getDayAdvance();
        $dayPayment = $monthDay->getDayPayment();
        $countWorkDays = $monthDay->getDaysCount();
        $countHours = $monthDay->getDaySalary();
        $holidays = $monthDay->getWeekends();

        if (strlen($holidays)>0) {
            $holidaysParts = explode(',', $holidays);
            foreach ($holidaysParts as $holidayPart) {
                $holiday[] = $holidayPart;
            }
            if (count($holidaysParts) == 1) {
                $holidays .= 'e';
            } else {
                $holidays = str_replace(',', 'e, ', $holidays);
                $holidays .= 'e';
            }
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
            if (in_array($dateValue['day'], $holiday)) {
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
        /** @var  $departmentPeopleRepository \Lists\DepartmentBundle\Entity\departmentPeopleRepository */
        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        if (!$departmentIsArray) {
            $coworkersAll = $departmentPeopleRepository->getOrderedPeopleFromDepartment($idDepartment);
            /** @var  $coworkersOne \Lists\DepartmentBundle\Entity\departmentPeople */
            foreach ($coworkersAll as $key => $departmentPeople) {
                if ($departmentPeople['dismissalDateNotOfficially'] != null
                    && $departmentPeople['dismissalDateNotOfficially'] < new \DateTime($year.'-'.$month)) {
                    unset($coworkersAll[$key]);
                }
            }
        } else {
            $coworkersAll = array();
        }

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $orders = $this->getOrdering($filterNamespace);

        $query = $monthInfoRepository->getFilteredCoworkers($idDepartment, $month, $year, $filters, $orders);

        $coworkers = $query->getResult();

        $infoHours = array();

        /** @var $coworker \Lists\DepartmentBundle\Entity\DepartmentPeople   */
        foreach ($coworkers as $coworker) {

            $idCoworker = $coworker['id'];

            $idReplacement = $coworker['replacementId'];

            $idDepartment = $coworker['idDepartment'];

            $infoHoursCoworker = $grafikRepository->getCoworkerHoursMonthInfo(
                $year,
                $month,
                $idDepartment,
                $idCoworker,
                $idReplacement
            );

            $infoHours[$idCoworker.'-'.$idReplacement]['salaryOfficially'] = 0;

            if (isset($coworker['salaryOfficially'])) {
                $infoHours[$idCoworker.'-'.$idReplacement]['salaryOfficially'] = $coworker['salaryOfficially'];
            }

            $infoHours[$idCoworker.'-'.$idReplacement]['realSalary'] = 0;

            if (isset($coworker['realSalary'])) {
                $infoHours[$idCoworker.'-'.$idReplacement]['realSalary'] = $coworker['realSalary'];
            }

            $infoHours[$idCoworker.'-'.$idReplacement]['officially'] = $grafikRepository->getSumTotalOfficially(
                $year,
                $month,
                $idDepartment,
                $idCoworker,
                $idReplacement
            );
            $infoHours[$idCoworker.'-'.$idReplacement]['notOfficially'] = $grafikRepository->getSumTotalNotOfficially(
                $year,
                $month,
                $idDepartment,
                $idCoworker,
                $idReplacement
            );

            $infoHours[$idCoworker.'-'.$idReplacement]['accrual'] = $this->getTotalOnceOnlyAccruals(
                $month,
                $year,
                $idCoworker
            );

            $plannedAccrualRepository = $this->getDoctrine()
                ->getRepository('ListsDepartmentBundle:PlannedAccrual');

            $plannedAccrual = $plannedAccrualRepository->findOneBy(
                array(
                    'departmentPeople' => $idCoworker,
                    'code' => 'UU',
                    'isActive' => true
                ),
                array(
                    'period' =>'desc'
                )
            );

            if ($plannedAccrual) {
                $infoHours[$idCoworker.'-'.$idReplacement]['plannedAccrual'] = $plannedAccrual->getValue();
            } else {
                $infoHours[$idCoworker.'-'.$idReplacement]['plannedAccrual'] = 0;
            }

            $infoHours[$idCoworker.'-'.$idReplacement]['salaryNotOfficially'] = $coworker['salaryNotOfficially'];
            if (!$infoHours[$idCoworker.'-'.$idReplacement]['salaryNotOfficially']) {
                $infoHours[$idCoworker.'-'.$idReplacement]['salaryNotOfficially'] =
                    $infoHours[$idCoworker.'-'.$idReplacement]['plannedAccrual'];
                $infoHours[$idCoworker.'-'.$idReplacement]['salaryNotOfficially'] +=
                    $infoHours[$idCoworker.'-'.$idReplacement]['accrual']['notOfficially']['plus'];
                $infoHours[$idCoworker.'-'.$idReplacement]['salaryNotOfficially'] -=
                    $infoHours[$idCoworker.'-'.$idReplacement]['accrual']['notOfficially']['minus'];
            }

            $infoHours[$idCoworker.'-'.$idReplacement]['totalSalary'] =
                $infoHours[$idCoworker.'-'.$idReplacement]['salaryOfficially']
                + $infoHours[$idCoworker.'-'.$idReplacement]['salaryNotOfficially'];


            foreach ($infoHoursCoworker as $infoDay) {
                $status = 'ok';
                if ($infoDay['isVacation']) {
                    $status = 'vacation';
                }
                if ($infoDay['isOwnVacation']) {
                    $status = 'ownVacation';
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

                $infoHours[$idCoworker.'-'.$idReplacement.'-'.$infoDay['day']]['status'] = $status;
                $infoHours[$idCoworker.'-'.$idReplacement.'-'.$infoDay['day']]['officially'] = $infoDay['total'];
                $infoHours[$idCoworker.'-'.$idReplacement.'-'.$infoDay['day']]['notOfficially'] =
                    $infoDay['totalNotOfficially'];

            }
            //var_dump(count($infoHours));
            $infoHours[$idCoworker.'-'.$idReplacement]['idMonthInfo'] = $coworker['idMonthInfo'];
        }

        $canEdit = $this->checkIfCanEdit();
        $infoSalary = $this->getSumsSalary($idDepartment, $year.'-'.$monthShow);
        $totalSalary = $infoSalary['totalSalary'];
        $salaryNotOfficially = $infoSalary['salaryNotOfficially'];
        $salaryOfficially = $infoSalary['salaryOfficially'];
        $idDepartment = $id;

        return $this->render('ITDoorsOperBundle:Schedule:scheduleTable.html.twig', array(
            'days'=> $days,
            'coworkers' => $coworkers,
            'dateInfo' => $dateInfo,
            'idDepartment' => $idDepartment,
            'infoHours' => $infoHours,
            'year' => $year,
            'month' => $month,
            'monthName' => $monthName,
            'filterCoworkers' => $coworkersAll,
            'workDaysTotal' => $countWorkDays,
            'hoursTotal' => $countHours,
            'holydaysTotalString' => $holidays,
            'dayAdvance' => $dayAdvance,
            'dayPayment' => $dayPayment,
            'canEdit' => $canEdit,
            'totalSalary' => $totalSalary,
            'salaryOfficially' => $salaryOfficially,
            'salaryNotOfficially' => $salaryNotOfficially,
            'departmentIsArray' => $departmentIsArray
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
        $idReplacement = $request->request->get('idReplacement');

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
            $idCoworker,
            $idReplacement
        );

        //var_dump($coworkerDayTime[0]);
        $return = array();

        $monthName = date("F", strtotime($date));
        $dayName = date('D', strtotime($date));


        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');


        $infoDay = $grafikRepository->getCoworkerHoursDayInfo(
            $day,
            $year,
            $month,
            $idDepartment,
            $idCoworker,
            $idReplacement
        );

            $status = 'ok';
        if (isset($infoDay[0])) {
            if ($infoDay[0]['isVacation']) {
                $status = 'vacation';
            }
            if ($infoDay[0]['isOwnVacation']) {
                $status = 'ownVacation';
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

        $stringDay = date('l', strtotime($date));

        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $departmentPeople = $departmentPeopleRepository->find($idCoworker);

        $fio = '';
        if ($departmentPeople->getIndividual()) {
            $individual = $departmentPeople->getIndividual();
            $fio = $individual->getLastName().' '.
                $individual->getFirstName().' '.$individual->getMiddleName();
        } else {
            $fio = $departmentPeople->getLastName().' '.
                $departmentPeople->getFirstName().' '.$departmentPeople->getMiddleName();
        }
        $mpk = $departmentPeople->getMpks();

        $canEdit  =  $this->checkIfCanEdit();

        $coworkersAll = $departmentPeopleRepository->getOrderedPeopleFromDepartment($idDepartment);
        /** @var  $coworkersOne \Lists\DepartmentBundle\Entity\departmentPeople */
        foreach ($coworkersAll as $key => $departmentPeople) {
            if ($departmentPeople['dismissalDateNotOfficially'] != null
                && $departmentPeople['dismissalDateNotOfficially'] < new \DateTime($year.'-'.$month)) {
                unset($coworkersAll[$key]);
            }
        }
        $coopration = array();
        $coopration['exists'] = false;
        if (isset($infoDay[0]) && $infoDay[0]['departmentPeopleCooperationId']) {
            $departmentPeopleCooperation = $departmentPeopleRepository->find($infoDay[0]['departmentPeopleCooperationId']);
            //$departmentPoopleCooperation = $grafik->getDepartmentPeopleCooperation();

            $idCooperatinon = $departmentPeopleCooperation->getId();

            if ($idCooperatinon) {
                $coopration['exists'] = true;
                $fioCooperation = '';
                $percentCooperation = $infoDay[0]['percentCooperation'];
                if ($departmentPeopleCooperation->getIndividual()) {
                    $individualCooperation = $departmentPeopleCooperation->getIndividual();
                    $fioCooperation = $individualCooperation->getLastName().' '.
                        $individualCooperation->getFirstName().' '.$individualCooperation->getMiddleName();
                } else {
                    $fioCooperation = $departmentPeopleCooperation->getLastName().' '.
                        $departmentPeopleCooperation->getFirstName().' '.$departmentPeopleCooperation->getMiddleName();
                }
                $mpkCooperation = $departmentPeopleCooperation->getMpks();
                $coopration['percent'] = $percentCooperation;
                $coopration['fio'] = $fioCooperation;
                $coopration['mpk'] = $mpkCooperation;
            }
        }

        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleDay.html.twig', array(
            'coworkerDayTime' => $coworkerDayTime,
            'date' => $date,
            'idCoworker' => $idCoworker,
            'idDepartment' => $idDepartment,
            'idReplacement' => $idReplacement,
            'monthName' => $monthName,
            'dayName' => $dayName,
            'day' => $day,
            'year' => $year,
            'idLink' => $idLink,
            'status' => $status,
            'stringDay' => $stringDay,
            'fio' => $fio,
            'mpk' => $mpk,
            'canEdit' => $canEdit,
            'filterCoworkers' => $coworkersAll,
            'cooperation' => $coopration
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
        $idReplacement = $request->request->get('idReplacement');

        $addTypeOfficially = '';

        if ($officially == 'false') {
            $officially = false;
            //we will need this down the code
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
            $idTimeGrafik,
            $idReplacement
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
            ->find($idReplacement);

        //array of points during the day which make periods of the day(evening, night, etc)
        $periodPoints[] = 6;
        $periodPoints[] = 18;
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

        $canEdit = $this->checkIfCanEdit();

        $return['html'] .= $this->renderView('ITDoorsOperBundle:Schedule:scheduleDayTableRow.html.twig', array(
            'oneDayTime'=> $newTime,
            'canEdit' =>$canEdit
        ));


        $this->updateSumGrafik($day, $month, $year, $idCoworker, $idDepartment, $idReplacement);
    //}

        return new Response(json_encode($return));
    }

    /**
     * Render one row of the table in schedule of one day
     *
     * @param mixed[] $oneDayTime
     * @param boolean $canEdit
     *
     * @return Response
     */
    public function scheduleTableRowAction($oneDayTime, $canEdit)
    {
        return $this->render('ITDoorsOperBundle:Schedule:scheduleDayTableRow.html.twig', array(
            'oneDayTime' => $oneDayTime,
            'canEdit' => $canEdit
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
        $idReplacement = $request->request->get('idReplacement');

        list($year, $month, $day) = explode('-', $date);

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');

        /** @var $grafik\Lists\GrafikBundle\Entity\GrafikTime   */
        $grafik = $grafikTimeRepository->find($idGrafikTime);
        $officially = !$grafik->getNotOfficially();

        $return = $this->checkErrorsForPeriods($date, $officially);

        if ($return['success'] == 0) {
            return new Response(json_encode($return));
        }

        if (!$grafik) {
            $return = array();
            $return['success'] = 0;
            $return['error'] = 'no_entity_found';

            return new Response(json_encode($return));
        }

        $em =  $this->getDoctrine()->getManager();
        $em->remove($grafik);
        $em->flush();

        $this->updateSumGrafik($day, $month, $year, $idCoworker, $idDepartment, $idReplacement);

        $return['success'] = 1;

        return new Response(json_encode($return));

    }

    /**
     * @param int $day
     * @param int $month
     * @param int $year
     * @param int $idCoworker
     * @param int $idDepartment
     * @param int $idReplacement
     */
    private function updateSumGrafik($day, $month, $year, $idCoworker, $idDepartment, $idReplacement = 0)
    {

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');

        $coworkerDayTimes = $grafikTimeRepository->getCoworkerHoursDayInfo(
            $year,
            $month,
            $day,
            $idDepartment,
            $idCoworker,
            $idReplacement
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
            ->find($idReplacement);

        /** @var  $grafik \Lists\GrafikBundle\Entity\Grafik*/
        $grafik = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik')
            ->findOneBy(array(
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'departmentPeople' => $departmentPeople,
                //'department' => $department,
                'departmentPeopleReplacement' => $departmentPeopleReplacement,
                'replacementType' => DepartmentPeopleMonthInfoRepository::REPLACEMENT_TYPE_REPLACEMENT
            ));

        if ($grafik == null) {
            $grafik = new Grafik();
            $grafik->setDay($day);
            $grafik->setMonth($month);
            $grafik->setYear($year);
            $grafik->setDepartmentPeople($departmentPeople);
            $grafik->setDepartment($department);
            $grafik->setReplacementType(DepartmentPeopleMonthInfoRepository::REPLACEMENT_TYPE_REPLACEMENT);
            $grafik->setDepartmentId($idDepartment);
            $grafik->setDepartmentPeopleId($idCoworker);
            $grafik->setDepartmentPeopleReplacement($departmentPeopleReplacement);
            $grafik->setDepartmentPeopleReplacementId($departmentPeopleReplacement);
        }
        $grafik->setDepartment($department);
        $grafik->setDepartmentId($idDepartment);
        $grafik->setTotal($total);
        $grafik->setTotalDay($totalDay);
        $grafik->setTotalEvening($totalEvening);
        $grafik->setTotalNight($totalNight);
        $grafik->setTotalNotOfficially($totalNotOfficially);
        $grafik->setTotalDayNotOfficially($totalDayNotOfficially);
        $grafik->setTotalEveningNotOfficially($totalEveningNotOfficially);
        $grafik->setTotalNightNotOfficially($totalNightNotOfficially);

        $em = $this->getDoctrine()->getManager();
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
        $idReplacement = $params['idReplacement'];
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
            $idCoworker,
            $idReplacement
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
            if ($infoDay[0]['isOwnVacation']) {
                $status = 'ownVacation';
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
    public function updateStatusCoworkerAction(Request $request)
    {
        $status = $request->request->get('status');
        $date =  $request->request->get('date');
        $idCoworker = $request->request->get('idCoworker');
        $idDepartment = $request->request->get('idDepartment');
        $idReplacement = $request->request->get('idReplacement');

        $return = $this->checkErrorsForPeriods($date, true);
        if ($return['success'] == 0) {
            return new Response(json_encode($return));
        }
        list($year, $month, $day) = explode('-', $date);

        $em =  $this->getDoctrine()->getManager();

        $department  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->find($idDepartment);

        $departmentPeople  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find($idCoworker);

        $departmentPeopleReplacement  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find($idReplacement);

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');



        $deleteGrafikTimes = $grafikTimeRepository->findBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $day,
            'year' => $year,
            'month' => $month,
            'departmentPeopleReplacement' => $idReplacement
        ));

        foreach ($deleteGrafikTimes as $deleteGrafikTime) {
            $em->remove($deleteGrafikTime);
            $em->flush();
        }

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
        $grafik->setTotal(0);
        $grafik->setTotalNotOfficially(0);
        $grafik->setTotalDay(0);
        $grafik->setTotalDayNotOfficially(0);
        $grafik->setTotalEvening(0);
        $grafik->setTotalEveningNotOfficially(0);
        $grafik->setTotalNight(0);
        $grafik->setTotalNightNotOfficially(0);

        $departmentPeopleZero  = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find(0);
        $grafik->setDepartmentPeopleCooperation($departmentPeopleZero);
        $grafik->setDepartmentPeopleCooperationId(0);
        $grafik->setPercentCooperation(0);


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
        if ($status == 'ownVacation') {
            $grafik->setIsOwnVacation(true);
        }

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
        $idReplacement = $request->request->get('idReplacement');

        list($year, $month, $day) = explode('-', $currentDate);

        foreach ($dates as $key => $date) {
            $dates[$key] = date('j', strtotime($date));
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
            'day' => intval($day),
            'year' => $year,
            'month' => $month,
            'departmentPeopleReplacement' => $idReplacement
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
        $coworkerDayTimes = array();
        foreach ($dates as $dayCopy) {
            $founded = $grafikTimeRepository->findBy(array(
                'department' => $idDepartment,
                'departmentPeople' => $idCoworker,
                'day' => intval($dayCopy),
                'year' => $year,
                'month' => $month,
                'departmentPeopleReplacement' => $idReplacement
            ));
            if (count($founded) > 1) {
                foreach($founded as $found) {
                    $coworkerDayTimes[] = $found;
                }
            } elseif (isset($founded[0]) && $founded[0]) {
                $coworkerDayTimes[] = $founded[0];
            }
        }

        $em =  $this->getDoctrine()->getManager();

        foreach ($coworkerDayTimes as $coworkerDayTime) {
            $em->remove($coworkerDayTime);
            $em->flush();
        }



        //deleting old day grafik
        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');
        $coworkerDayTimesTotal = array();
        foreach ($dates as $dayCopy) {
            $founded = $grafikRepository->findOneBy(array(
                'department' => $idDepartment,
                'departmentPeople' => $idCoworker,
                'day' => intval($dayCopy),
                'year' => $year,
                'month' => $month,
                'departmentPeopleReplacement' => $idReplacement

            ));
            if ($founded) {
                $coworkerDayTimesTotal[] = $founded;
            }
        }
        foreach ($coworkerDayTimesTotal as $coworkerDayTimeTotal) {
            $em->remove($coworkerDayTimeTotal);
            $em->flush();
        }

        //copying new daytime to grafik time
        /** @var  $copyGrafikTime \Lists\GrafikBundle\Entity\GrafikTime */
        $copyGrafikTimes = $grafikTimeRepository->findBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $day,
            'year' => $year,
            'month' => $month,
            'departmentPeopleReplacement' => $idReplacement

        ));

        foreach ($copyGrafikTimes as $copyGrafikTime) {
            foreach ($dates as $dayNew) {
                $cloneGrafikTime = clone $copyGrafikTime;
                $cloneGrafikTime->setDay(intval($dayNew));
                $em->persist($cloneGrafikTime);
            }
        }
        $em->flush();

        //copying new daytime to grafik
        /** @var  $copyGrafik \Lists\GrafikBundle\Entity\Grafik */
        $copyGrafik = $grafikRepository->findOneBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'day' => $day,
            'year' => $year,
            'month' => $month,
            'departmentPeopleReplacement' => $idReplacement
        ));
        if ($copyGrafik) {
            foreach ($dates as $dayNew) {
                $copyGrafik = clone $copyGrafik;
                $copyGrafik->setDay(intval($dayNew));
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
/*        } elseif ($departmentPeople->getAdmissionDate() != null
            && $departmentPeople->getAdmissionDate() < new \DateTime($date)
            && $departmentPeople->getDismissalDate() != null
            && $departmentPeople->getDismissalDate() > new \DateTime($date)
            && $officially) {

            $return['success'] = 0;
            $return['error'] = 'no_official_permitted';

            return $return;*/
        } elseif (($departmentPeople->getAdmissionDate() != null
            && $departmentPeople->getAdmissionDate() > new \DateTime($date)
            && $officially) ||
            ($departmentPeople->getDismissalDate() != null
            && $departmentPeople->getDismissalDate() < new \DateTime($date)
            && $officially)) {

            $return['success'] = 0;
            $return['error'] = 'no_official_permitted';

            return $return;
        }

        $return = $this->checkErrorsForPeriods($date, $officially);
        if ($return['success'] == 0) {

            return $return;
        }


        $return['success'] = 1;

        return $return;
    }

    /**
     * @param string  $date
     * @param boolean $officially
     *
     * @return mixed
     */
    private function checkErrorsForPeriods($date, $officially)
    {
        $currentDate = date('Y-m-d');
        list($year, $month, $day) = explode('-', $currentDate);

        $monthDaysRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Salary');

        $monthDay = $monthDaysRepository->findOneBy(array(
            'year' => $year,
            'month' =>$month
        ));

        $advanceDateCurrent = $monthDay->getDayAdvance();
        $paymentDateCurrent = $monthDay->getDayPayment();

        list($year, $month, $day) = explode('-', $date);
        $monthDay = $monthDaysRepository->findOneBy(array(
            'year' => $year,
            'month' =>$month
        ));

        $advanceDate = $monthDay->getDayAdvance();
        $paymentDate = $monthDay->getDayPayment();


        if (new \DateTime($date) < $advanceDateCurrent && $officially && $advanceDate < new \DateTime($currentDate)) {
            $return['success'] = 0;
            $return['error'] = 'advance_passed';

            return $return;
        }

        if (new \DateTime($date) < $paymentDateCurrent && $paymentDate < new \DateTime($currentDate)) {
            $return['success'] = 0;
            $return['error'] = 'payment_passed';

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
        $idsDepartment = $request->request->get('idsDepartment');
        $date = $request->request->get('date');
        $idsReplacement = $request->request->get('idsReplacement');
        $idDepartmentSelected = $request->request->get('idDepartmentSelected');
        $idReplacementSelected = $request->request->get('idReplacementSelected');
        $return['test'] = $idDepartmentSelected;

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

        foreach ($idsCopy as $keyCopy => $idCopy) {
            for ($dayCopy=1; $dayCopy<=date("t", strtotime($year.'-'.$month)); $dayCopy++) {
                $copyGrafikTimes = $grafikTimeRepository->findBy(array(
                    'department' => $idDepartmentSelected,
                    'departmentPeople' => $idSelected,
                    'day' => $dayCopy,
                    'year' => $year,
                    'month' => $month,
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
            $return['success'] = 0;

            return new Response(json_encode($return));
        }

        $em =  $this->getDoctrine()->getManager();


        foreach ($idsCopy as $key => $idCopy) {
            $departmentPeople  = $this->getDoctrine()
                ->getRepository('ListsDepartmentBundle:DepartmentPeople')
                ->find($idCopy);
            $departmentPeopleReplacement  = $this->getDoctrine()
                ->getRepository('ListsDepartmentBundle:DepartmentPeople')
                ->find($idsReplacement[$key]);

            $department = $this->getDoctrine()
                ->getRepository('ListsDepartmentBundle:Departments')
                ->find($idsDepartment[$key]);

            //deleting old day grafik times
            $coworkerDayTimes = $grafikTimeRepository->findBy(array(
                'department' => $idsDepartment[$key],
                'departmentPeople' => $idCopy,
                'year' => $year,
                'month' => $month,
                'departmentPeopleReplacement' => $idsReplacement[$key]
            ));

            foreach ($coworkerDayTimes as $coworkerDayTime) {
                $em->remove($coworkerDayTime);
            }
            //deleting old day grafik
            $coworkerDayTimesTotal = $grafikRepository->findBy(array(
                'department' => $idsDepartment[$key],
                'departmentPeople' => $idCopy,
                'year' => $year,
                'month' => $month,
                'departmentPeopleReplacement' => $idsReplacement[$key]
            ));
            foreach ($coworkerDayTimesTotal as $coworkerDayTimeTotal) {
                $em->remove($coworkerDayTimeTotal);
            }
            $em->flush();

            //copying new daytime to grafik time
            /** @var  $copyGrafikTime \Lists\GrafikBundle\Entity\GrafikTime */
            $copyGrafikTimes = $grafikTimeRepository->findBy(array(
                'department' => $idDepartmentSelected,
                'departmentPeople' => $idSelected,
                'year' => $year,
                'month' => $month,
                'departmentPeopleReplacement' => $idReplacementSelected
            ));
            if (count($copyGrafikTimes) > 0) {
                foreach ($copyGrafikTimes as $copyGrafikTime) {
                        $cloneGrafikTime = clone $copyGrafikTime;
                        $cloneGrafikTime->setDepartment($department);
                        $cloneGrafikTime->setDepartmentPeople($departmentPeople);
                        $cloneGrafikTime->setDepartmentPeopleReplacement($departmentPeopleReplacement);
                        $em->persist($cloneGrafikTime);
                }
                $em->flush();
            }

            //copying new daytime to grafik
            /** @var  $copyGrafik \Lists\GrafikBundle\Entity\Grafik */
            $copyGrafiks = $grafikRepository->findBy(array(
                'department' => $idDepartmentSelected,
                'departmentPeople' => $idSelected,
                'year' => $year,
                'month' => $month,
                'departmentPeopleReplacement' => $idReplacementSelected
            ));
            if (count($copyGrafiks) > 0) {
                foreach ($copyGrafiks as $copyGrafik) {
                    $cloneGrafik = clone $copyGrafik;
                    $cloneGrafik->setDepartmentPeople($departmentPeople);
                    $cloneGrafik->setDepartment($department);
                    $cloneGrafik->setDepartmentPeopleId($idCopy);
                    $cloneGrafik->setDepartmentPeopleReplacement($departmentPeopleReplacement);

                    $em->persist($cloneGrafik);
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
        $idReplacement = $params['idReplacement'];
        $replacementType = isset($params['replacementType'])
                           ? $params['replacementType']
                           : DepartmentPeopleMonthInfoRepository::REPLACEMENT_TYPE_REPLACEMENT;

        list($year, $month) = explode('-', $date);

        //$date = $request->request->get('date');

        $return['html'] = '';
        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');
        /** @var  $departmentPeople \Lists\DepartmentBundle\Entity\DepartmentPeople */
        $departmentPeople = $departmentPeopleRepository->find($idCoworker);

        $fio = '';
        if ($departmentPeople->getIndividual()) {
            $individual = $departmentPeople->getIndividual();
            $fio = $individual->getLastName().' '.
                $individual->getFirstName().' '.$individual->getMiddleName();
        } else {
            $fio = $departmentPeople->getLastName().' '.
                $departmentPeople->getFirstName().' '.$departmentPeople->getMiddleName();
        }

        // Get Organiation Name
        $organizationName = $departmentPeople->getOrganizationName();

        $info['id'] = $departmentPeople->getId();
        $info['mpk'] = $departmentPeople->getMpks();
        $info['organizationName'] = $organizationName;
        if ($departmentPeople->getMpks()->getOrganization()) {
            $info['selfOrganizationName'] = $departmentPeople->getMpks()->getOrganization()->getName();
        } else {
            $info['selfOrganizationName'] = '';
        }
            $info['fio'] = $fio;
        $info['dateAcceptedOfficially'] = $departmentPeople->getAdmissionDate();
        $info['dateAcceptedNotOfficially'] = $departmentPeople->getAdmissionDateNotOfficially();
        $info['dateFiredOfficially'] = $departmentPeople->getDismissalDate();
        $info['dateFiredNotOfficially'] = $departmentPeople->getDismissalDateNotOfficially();
        $info['gph'] = $departmentPeople->getIsGph();
        $info['change'] = false;
        if ($idReplacement > 0) {
            $info['change'] = true;
            $departmentPeopleReplacement = $departmentPeopleRepository->find($idReplacement);
            $info['changeFio'] = '';
            if ($departmentPeopleReplacement->getIndividual()) {
                $individual = $departmentPeopleReplacement->getIndividual();
                $info['changeFio'] = $individual->getLastName().' '.
                    $individual->getFirstName().' '.$individual->getMiddleName();
            } else {
                $info['changeFio'] = $departmentPeopleReplacement->getLastName().' '.
                    $departmentPeopleReplacement->getFirstName().' '.$departmentPeopleReplacement->getMiddleName();
            }
            $info['changeMpk'] = $departmentPeopleReplacement->getMpks();
        }
        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array(
            'departmentPeople' => $departmentPeople->getId(),
            'year' => $year,
            'month' => $month,
            'replacementType' => DepartmentPeopleMonthInfoRepository::REPLACEMENT_TYPE_REPLACEMENT,
            //'departmentPeopleReplacement' => 0

        ));


        $onceOnlyAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:OnceOnlyAccrual');

        $onceOnlyAccrual = $onceOnlyAccrualRepository->findBy(array(
            'departmentPeopleMonthInfo'=>$monthInfo->getId()
        ));


        $accrual['officially'] = array();
        $accrual['notOfficially'] = array();

        $plannedAccrualRepository = $this->getDoctrine()
          ->getRepository('ListsDepartmentBundle:PlannedAccrual');

        $plannedAccrual = $plannedAccrualRepository->findBy(
            array(
                'departmentPeople' => $idCoworker
            ),
            array('period' => 'DESC')
        );

        $canEdit  =  $this->checkIfCanEdit();
        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleInfoUserBasic.html.twig', array(
            'coworker'=> $info,
            'accrual' => $onceOnlyAccrual,
            'planned' => $plannedAccrual,
            'idCoworker' => $idCoworker,
            'idReplacement' => $idReplacement,
            'idDepartment' => $idDepartment,
            'canEdit' => $canEdit,
            'replacementType' => $replacementType
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
            $return = $this->checkErrorsForChangingDate($idCoworker, $date.'-'.$i, $check);
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
            $code = 'uu';
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

        $canEdit = $this->checkIfCanEdit();
        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleInfoUserBasicAccrual.html.twig', array(
            'accruals'=> $onceOnlyAccrual,
            'code' => $code,
            'canEdit' => $canEdit
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

        $check = true;
        if ($deleteAccrual->getCode() == 'uu') {
            $check = false;
        }
        for ($i=1; $i<=date("t", strtotime($year.'-'.$month)); $i++) {
            $return = $this->checkErrorsForChangingDate($idCoworker, $date.'-'.$i, $check);
            if ($return['success'] == 1) {
                break;
            }
        }

        if ($return['success'] == 0) {

            return new Response(json_encode($return));
        }

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

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addUserToGrafikAction(Request $request)
    {
        $idCoworker = $request->request->get('idCoworker');
        $date = $request->request->get('date');
        $idCoworkerReplacement =  $request->request->get('idCoworkerReplacement');
        $type = $request->request->get('typeReplacement');

        list($year, $month) = explode('-', $date);

        $return = $this->checkErrorsForPeriods($date.'-01', false);
        if ($return['success'] == 0) {
            return new Response(json_encode($return));
        }
        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');
        /** @var  $departmentPeople \Lists\DepartmentBundle\Entity\DepartmentPeople */
        $departmentPeople = $departmentPeopleRepository->find($idCoworker);

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        if ($type == 'change') {
            $departmentPeopleReplacement = $departmentPeopleRepository->find($idCoworkerReplacement);
        } elseif ($type == 'permanent') {
            $departmentPeopleReplacement = $departmentPeopleRepository->find(0);
        }

        $monthInfo = $monthInfoRepository->findBy(array(
            'departmentPeople' => $departmentPeople,
            'month' => $month,
            'year' => $year,
            'departmentPeopleReplacement' => $departmentPeopleReplacement,
            'departmentPeopleId'=> $idCoworker

        ));



        if ($monthInfo) {
            $return['success'] = 0;
            $return['error'] = 'exists';

            return new Response(json_encode($return));
        }

        $monthInfo = new \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfo();
        $monthInfo->setYear($year);
        $monthInfo->setMonth($month);
        $monthInfo->setDepartmentPeople($departmentPeople);
        $monthInfo->setDepartmentPeopleId($idCoworker);
        $monthInfo->setDepartmentPeopleReplacement($departmentPeopleReplacement);
        $monthInfo->setReplacementType('r');

        $em = $this->getDoctrine()->getManager();
        $em->persist($monthInfo);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteUserFromGrafikAction(Request $request)
    {
        $idCoworker = $request->request->get('idCoworker');
        $date = $request->request->get('date');
        $idCoworkerReplacement =  $request->request->get('idReplacement');
        $idDepartment =  $request->request->get('idDepartment');
        $replacementType =  $request->request->get('replacementType');

        //var_dump($idCoworker, $date, $idCoworkerReplacement);
        list($year, $month) = explode('-', $date);

        $options = array(
            'year' => $year,
            'month' => $month,
            'departmentId' => $idDepartment,
            'departmentPeopleId' => $idCoworker,
            'replacementId' => $idCoworkerReplacement,
            'replacementType' => $replacementType
        );

        /** @var ScheduleService $scheduleService */
        $scheduleService = $this->get('schedule.service');

        $scheduleService->deleteUserFromGrafik($options);

        $return['success'] = $scheduleService ? 1 : 0;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function copyToNextMonthAction(Request $request)
    {
        $idDepartment = $request->request->get('idDepartment');
        $date = $request->request->get('date');

        list($year, $month) = explode('-', $date);

        /** @var ScheduleService $scheduleService */
        $scheduleService = $this->get('schedule.service');

        $scheduleService->copyToNextMonth($idDepartment, $year, $month);

        $return = array(
            'year' => $year,
            'month' => $month,
            'idDepartment' => $idDepartment
        );

        return new Response(json_encode($return));
    }

    /**
     * @param integer $month
     * @param integer $year
     * @param integer $idCoworker
     *
     * @return mixed[]
     */
    private function getTotalOnceOnlyAccruals($month, $year, $idCoworker)
    {

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array(
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            //'departmentPeopleReplacement' => 0

        ));


        $onceOnlyAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:OnceOnlyAccrual');


        $accrual['officially'] = array();
        $accrual['notOfficially'] = array();

        $accrual['officially']['plus'] = 0;
        $accrual['notOfficially']['plus'] = 0;

        $accrual['officially']['minus'] = 0;
        $accrual['notOfficially']['minus'] = 0;

        if (!$monthInfo) {

            return $accrual;
        }
        $onceOnlyAccruals = $onceOnlyAccrualRepository->findBy(array(
            'departmentPeopleMonthInfo'=>$monthInfo->getId()
        ));

        foreach ($onceOnlyAccruals as $onceOnlyAccrual) {
            if ($onceOnlyAccrual->getCode() == 'uu') {
                $key = 'notOfficially';
            } else {
                $key = 'officially';
            }
            if ($onceOnlyAccrual->getType() == 'fine') {
                $accrual[$key]['minus'] += $onceOnlyAccrual->getValue();
            } else {
                $accrual[$key]['plus'] += $onceOnlyAccrual->getValue();
            }
        }

        return $accrual;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getSumsRenderedAction(Request $request)
    {
        $params =  $request->request->get('params');

        $date =  $params['date'];
        $idCoworker = $params['idCoworker'];
        $idDepartment = $params['idDepartment'];
        $idReplacement = $params['idReplacement'];

        $info = $this->getSumsCoworker($date, $idCoworker, $idReplacement, $idDepartment);
        $return['html'] = $this->renderView('ITDoorsOperBundle:Schedule:scheduleTableSums.html.twig', $info);
        $infoSalary = $this->getSumsSalary($idDepartment, $date);
        $return += $infoSalary;

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param string  $date
     * @param integer $idCoworker
     * @param integer $idReplacement
     * @param integer $idDepartment
     *
     * @return Response
     */
    public function getRowSumsRenderedAction($date, $idCoworker, $idReplacement, $idDepartment)
    {
        $info = $this->getSumsCoworker($date, $idCoworker, $idReplacement, $idDepartment);
        $return  = $this->renderView('ITDoorsOperBundle:Schedule:scheduleTableSums.html.twig', $info);

        return new Response($return);
    }

    /**
     * @param string  $date
     * @param integer $idCoworker
     * @param integer $idReplacement
     * @param integer $idDepartment
     *
     * @return array
     */
    private function getSumsCoworker($date, $idCoworker, $idReplacement, $idDepartment)
    {
        list($year, $month) = explode('-', $date);

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->findOneBy(array(
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'replacementType' => 'r',
            'departmentPeopleReplacement' => $idReplacement

        ));

        $idMonthInfo = $monthInfo->getId();


        if (isset($monthInfo)) {
            $salaryOfficially = $monthInfo->getSalaryOfficially();
            if (!$salaryOfficially) {
                $salaryOfficially = 0;
            }

            $realSalary = $monthInfo->getRealSalary();
            if (!$realSalary) {
                $realSalary = 0;
            }
        }

        $officiallyTotal = $grafikRepository->getSumTotalOfficially(
            $year,
            $month,
            $idDepartment,
            $idCoworker,
            $idReplacement
        );
        $notOfficiallyTotal = $grafikRepository->getSumTotalNotOfficially(
            $year,
            $month,
            $idDepartment,
            $idCoworker,
            $idReplacement
        );

        $accrual = $this->getTotalOnceOnlyAccruals(
            $month,
            $year,
            $idCoworker
        );

        $plannedAccrualRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:PlannedAccrual');

        $plannedAccrual = $plannedAccrualRepository->findOneBy(
            array(
                'departmentPeople' => $idCoworker,
                'code' => 'UU',
                'isActive' => true
            ),
            array(
                'period' => 'desc'
            )
        );

        if ($plannedAccrual) {
            $plannedAccrualValue = $plannedAccrual->getValue();
        } else {
            $plannedAccrualValue = 0;
        }
        $salaryNotOfficially = $monthInfo->getSalaryNotOfficially();
        if (!$salaryNotOfficially) {
            $salaryNotOfficially = $plannedAccrualValue;
            $salaryNotOfficially += $accrual['notOfficially']['plus'];
            $salaryNotOfficially -= $accrual['notOfficially']['minus'];
        }

        $totalSalary = $salaryOfficially + $salaryNotOfficially;
        $canEdit  =  $this->checkIfCanEdit();
        $return = array(
            'sumOfficially' => $officiallyTotal,
            'sumNotOfficially' => $notOfficiallyTotal,
            'plannedAccrual' => $plannedAccrualValue,
            'accrual' => $accrual,
            'salaryOfficially' => $salaryOfficially,
            'idCoworker' => $idCoworker,
            'idReplacement' => $idReplacement,
            'realSalary' => $realSalary,
            'salaryNotOfficially' => $salaryNotOfficially,
            'totalSalary' => $totalSalary,
            'idMonthInfo'=> $idMonthInfo,
            'canEdit' => $canEdit,
            'idDepartment' => $idDepartment
        );

        return $return;
    }

    /**
     * @param integer $idDepartment
     * @param string  $date
     *
     * @return array
     */
    private function getSumsSalary($idDepartment, $date)
    {
        $totalSalary = 0;
        $salaryNotOfficially = 0;
        $salaryOfficially = 0;
        list($year, $month) = explode('-', $date);

        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $departmentPeoples = $departmentPeopleRepository->findBy(
            array(
                'department' => $idDepartment,
            )
        );


        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        foreach ($departmentPeoples as $departmentPeople) {
            $monthInfos = $monthInfoRepository->findBy(array(
                'year' => $year,
                'month' => $month,
                'departmentPeople' => $departmentPeople
            ));

            /** @var  $monthInfo \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfo */
            foreach ($monthInfos as $monthInfo) {
                $idCoworker = $monthInfo->getDepartmentPeopleId();
                $idReplacement = $monthInfo->getReplacementId();
                $info = $this->getSumsCoworker($date, $idCoworker, $idReplacement, $idDepartment);

                $totalSalary += $info['totalSalary'];
                $salaryNotOfficially += $info['salaryNotOfficially'];
                $salaryOfficially += $info['salaryOfficially'];
            }
        }

        $return = array(
            'totalSalary' => $totalSalary,
            'salaryNotOfficially' => $salaryNotOfficially,
            'salaryOfficially' => $salaryOfficially
        );

        return $return;
    }
    /**
     * @return Response
     */
    public function ajaxEditingSalaryAction()
    {
        $idMonthInfo = $this->get('request')->request->get('pk');
        $value = $this->get('request')->request->get('value');

        /** @var  $monthInfoRepository \Lists\DepartmentBundle\Entity\departmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->find($idMonthInfo);

        $monthInfo->setSalaryNotOfficially($value);

        $em = $this->getDoctrine()->getManager();
        $em->persist($monthInfo);
        $em->flush();

        $result = array(
            'id' => $idMonthInfo,
            'value' => $value,
            'name' => '1',
            'text' => (string) $value
        );

        return new Response(json_encode($result));

    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function changeCooperationAction(Request $request)
    {
        $idDepartment = $request->request->get('idDepartment');
        $date = $request->request->get('date');
        $idCoworker = $request->request->get('idCoworker');
        $idCooperation  = $request->request->get('idCooperation');
        $idReplacement = $request->request->get('idReplacement');
        $percent = $request->request->get('percent');

        $percent = str_replace('%', '', $percent);
        $percent = str_replace(',', '.', $percent);

        list($year, $month, $day) = explode('-', $date);

        $result = $this->checkErrorsForPeriods($date, false);

        if ($result['success'] == 0) {
            return new Response(json_encode($result));
        }

        $departmentPeopleRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:DepartmentPeople');

        $departmentPeopleReplacement = $departmentPeopleRepository
            ->find($idReplacement);

        $departmentPeople = $departmentPeopleRepository
            ->find($idCoworker);

        $departmentPeopleCooperation = $departmentPeopleRepository
            ->find($idCooperation);

        $department =  $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')
            ->find($idDepartment);

        /** @var $grafikRepository \Lists\GrafikBundle\Entity\GrafikRepository   */
        $grafikRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:Grafik');

        /** @var  $grafik \Lists\GrafikBundle\Entity\Grafik */
        $grafik = $grafikRepository->findOneBy(array(
            'department' => $idDepartment,
            'departmentPeople' => $idCoworker,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'departmentPeopleReplacement' => $departmentPeopleReplacement
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
            $grafik->setDepartmentPeopleReplacementId($idReplacement);

        }


        $grafik->setDepartmentPeopleCooperation($departmentPeopleCooperation);
        $grafik->setPercentCooperation($percent);
        $em = $this->getDoctrine()->getManager();
        $em->persist($grafik);
        $em->flush();

        $result = array();

        $result['success'] = 1;

        return new Response(json_encode($result));
    }

    /**
     * @return bool
     */
    private function checkIfCanEdit()
    {
        $canEdit = !$this->getUser()->hasRole('ROLE_SUPERVISOR');

        return $canEdit;
    }
}
