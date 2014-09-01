<?php
namespace ITDoors\OperBundle\Services;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Lists\DepartmentBundle\Entity\DepartmentPeopleMonthInfo;
use Lists\DepartmentBundle\Entity\DepartmentPeopleMonthInfoRepository;
use Lists\GrafikBundle\Entity\Grafik;
use Lists\GrafikBundle\Entity\GrafikTime;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ScheduleUpdateService
 */
class ScheduleUpdateService
{
    /** @var Container $container */
    protected $container;

    /** @var  EntityManager $em */
    protected $em;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }


    /**
     * @param int|array $months
     * @param int       $year
     */
    public function scheduleUpdate($months, $year)
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->container->get('doctrine')
            ->getRepository('ListsGrafikBundle:GrafikTime');

        $grafikTimes = $grafikTimeRepository->findBy(array(
            'month' => $months,
            'year' => $year
            )
        );

        $counter = count($grafikTimes);
        echo $counter.' all'."\n";
        echo 'starting re-count...'."\n";

        //array of points during the day which make periods of the day(evening, night, etc)
        $periodPoints = array();
        $periodPoints[] = 6;
        $periodPoints[] = 18;
        $periodPoints[] = 22;
        $periodPoints[] = 24;

        $currentMonth = 0;
        $currentDay = 0;
        $counterFlush = 0;
        /** @var $grafikTime \Lists\GrafikBundle\Entity\GrafikTime   */
        foreach ($grafikTimes as $grafikJoke) {

            $grafikTime = $this->container->get('doctrine')
                ->getRepository('ListsGrafikBundle:GrafikTime')->find($grafikJoke->getId());
            $counterFlush++;
            echo $counter.' id:'.$grafikJoke->getId()."\n";
            $counter--;
            if (!$grafikTime) {
                echo 'ERROR ERROR';
                continue;
            }
            $hoursFromDb = $grafikTime->getFromTime()->format("H:i");;
            $hoursToDb = $grafikTime->getToTime()->format("H:i");

            list($hoursFrom, $minutesFrom) = explode(':', $hoursFromDb);
            list($hoursTo, $minutesTo) = explode(':', $hoursToDb);
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

            if ($grafikTime->getNotOfficially() === true) {
                $cleanOtherOfficially = '';
                $addTypeOfficially = 'NotOfficially';
            } else {
                $cleanOtherOfficially = 'NotOfficially';
                $addTypeOfficially = '';
            }
            //$addTypeOfficially = $cleanOtherOfficially;

            $funcTotalClean = 'setTotal'.$cleanOtherOfficially;
            $funcTotalDayClean = 'setTotalDay'.$cleanOtherOfficially;
            $funcTotalEveningClean = 'setTotalEvening'.$cleanOtherOfficially;
            $funcTotalNightClean = 'setTotalNight'.$cleanOtherOfficially;

            $grafikTime->$funcTotalClean(0);
            $grafikTime->$funcTotalDayClean(0);
            $grafikTime->$funcTotalEveningClean(0);
            $grafikTime->$funcTotalNightClean(0);


            $funcTotal = 'setTotal'.$addTypeOfficially;
            $funcTotalDay = 'setTotalDay'.$addTypeOfficially;
            $funcTotalEvening = 'setTotalEvening'.$addTypeOfficially;
            $funcTotalNight = 'setTotalNight'.$addTypeOfficially;

            //setting official or not total hours
            $grafikTime->$funcTotal($total);
            $grafikTime->$funcTotalDay($totalDay);
            $grafikTime->$funcTotalEvening($totalEvening);
            $grafikTime->$funcTotalNight($totalNight);
            $this->em->persist($grafikTime);

            $day = $grafikTime->getDay();
            $month = $grafikTime->getMonth();
            $year = $grafikTime->getYear();
            $idCoworker = $grafikTime->getDepartmentPeopleId();
            $idDepartment = $grafikTime->getDepartmentId();
            $idReplacement = $grafikTime->getDepartmentPeopleReplacementId();

            if ($currentDay != $day || $currentMonth != $month) {
                $this->updateSumGrafik($day, $month, $year, $idCoworker, $idDepartment, $idReplacement);
                $currentMonth = $month;
                $currentDay = $day;
            }
            if ($counterFlush == 5000) {
                $this->em->flush();
                $this->em->clear();
                $counterFlush = 0;
            }
        }
        $this->em->flush();


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
        $grafikTimeRepository = $this->container->get('doctrine')
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

        $department  = $this->container->get('doctrine')
            ->getRepository('ListsDepartmentBundle:Departments')
            ->find($idDepartment);

        $departmentPeople  = $this->container->get('doctrine')
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find($idCoworker);

        $departmentPeopleReplacement  = $this->container->get('doctrine')
            ->getRepository('ListsDepartmentBundle:DepartmentPeople')
            ->find($idReplacement);

        /** @var  $grafik \Lists\GrafikBundle\Entity\Grafik*/
        $grafik = $this->container->get('doctrine')
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

/*        if ($grafik == null) {
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
        }*/
        if ($grafik != null) {
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

        //$em = $this->container->get('doctrine')->getManager();
        $this->em->persist($grafik);
        }

    }


    /**
     * @param int|array $months
     * @param int       $year
     */
    public function scheduleFix($months, $year)
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        /** @var $grafikTimeRepository \Lists\GrafikBundle\Entity\GrafikTimeRepository   */
        $grafikTimeRepository = $this->container->get('doctrine')
            ->getRepository('ListsGrafikBundle:GrafikTime');

        $grafikTimes = $grafikTimeRepository->findBy(array(
                'month' => $months,
                'year' => $year
            )
        );

        $counter = count($grafikTimes);
        echo $counter.' all'."\n";
        echo 'starting re-count...'."\n";

        $currentMonth = 0;
        $currentDay = 0;
        $counterFlush = 0;
        $deletedIds = array();
        /** @var $grafikTime \Lists\GrafikBundle\Entity\GrafikTime   */
        foreach ($grafikTimes as $grafikJoke) {
            if (!in_array($grafikJoke->getId(), $deletedIds)) {
                $grafikTime = $this->container->get('doctrine')
                    ->getRepository('ListsGrafikBundle:GrafikTime')->find($grafikJoke->getId());

                echo $counter.' id:'.$grafikJoke->getId()." delete:".$counterFlush."\n";
                $counter--;
                if (!$grafikTime) {
                    echo 'ERROR ERROR';
                    continue;
                }

                $day = $grafikTime->getDay();
                $month = $grafikTime->getMonth();
                $year = $grafikTime->getYear();
                $departmentPeople = $grafikTime->getDepartmentPeople();
                $department = $grafikTime->getDepartment();
                $replacementType= $grafikTime->getReplacementType();
                $idDepartment = $grafikTime->getDepartmentId();
                $idCoworker = $grafikTime->getDepartmentPeopleId();
                $departmentPeopleReplacement = $grafikTime->getDepartmentPeopleReplacement();
                $departmentPeopleReplacementId = $grafikTime->getDepartmentPeopleReplacementId();
                $fromTime = $grafikTime->getFromTime();
                $toTime = $grafikTime->getToTime();

                $subtimes = $this->container->get('doctrine')
                    ->getRepository('ListsGrafikBundle:GrafikTime')
                ->getSubtimeIds(
                    $year,
                    $month,
                    $day,
                    $idDepartment,
                    $idCoworker,
                    $fromTime,
                    $toTime,
                    $grafikTime->getId(),
                    $departmentPeopleReplacementId
                );
                $currentId = $grafikTime->getId();
                if (count($subtimes)) {
                    foreach ($subtimes as $subtime) {
                        if ($currentId>$subtime['id']) {
                            $this->em->remove($grafikTimeRepository->find($subtime['id']));
                            $deletedIds[] = $subtime['id'];
                        } /*else {
                            $this->em->remove($grafikTimeRepository->find($currentId));
                            $deletedIds[] = $currentId;
                        }*/
                        $counterFlush++;
                    }
                }

                if ($counterFlush == 500) {
                    $this->em->flush();
                    $this->em->clear();
                    $counterFlush = 0;
                }

            }

        }
        $this->em->flush();
    }


}
