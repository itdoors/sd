<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * OperInfoController class
 *
 * Default controller for oper page
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

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->getDoctrine()
            ->getRepository('ListsGrafikBundle:GrafikTime');
        $date = explode('-', $date);

        $day = $date[2];
        $month = $date[1];
        $year = $date[0];

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
        ));
        $return['success'] = 1;

        return new Response(json_encode($return));

    }
}
