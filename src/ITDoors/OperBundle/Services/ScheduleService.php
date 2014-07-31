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
 * Class ScheduleService
 */
class ScheduleService
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
     * @param int $departmentId
     * @param int $year
     * @param int $month
     *
     * @return bool
     */
    public function copyToNextMonth($departmentId, $year, $month)
    {
        $nextMonth = $month < 12 ? $month + 1 : 1;
        $nextYear = $month < 12 ? $year : $year + 1;

        /** var DepartmentPeopleMonthInfo[] $monthInfo */
        $monthInfo = $this->getPersistentPeople($departmentId, $year, $month);

        if (!sizeof($monthInfo)) {
            return true;
        }

        $holidays = $this->getAllWeekends($year, $month);

        $holidaysNext = $this->getAllWeekends($nextYear, $nextMonth);

        $firstWorkDayThisMonth = $this->getFirstWorkDay($year, $month, $holidays);
        $firstWorkDayNextMonth = $this->getFirstWorkDay($nextYear, $nextMonth, $holidaysNext);

        foreach ($monthInfo as $info) {
            $personId = $info->getDepartmentPeopleId();
            $replacementId = 0;

            $this->copyPersonToNextMonth(
                $personId,
                $replacementId,
                $departmentId,
                $year,
                $month,
                $holidaysNext,
                $firstWorkDayThisMonth,
                $firstWorkDayNextMonth
            );
        }

        return true;
    }

    /**
     * @param int   $personId
     * @param int   $replacementId
     * @param int   $departmentId
     * @param int   $year
     * @param int   $month
     * @param int[] $holidaysNext
     * @param int   $firstWorkDayThisMonth
     * @param int   $firstWorkDayNextMonth
     */
    public function copyPersonToNextMonth
    (
        $personId,
        $replacementId,
        $departmentId,
        $year,
        $month,
        $holidaysNext,
        $firstWorkDayThisMonth,
        $firstWorkDayNextMonth
    // @codingStandardsIgnoreStart
    )
    {
    // @codingStandardsIgnoreEnd
        if ($month < 12) {
            $nextYear = $year;
            $nextMonth = $month + 1;

            $nextYearString = 'year';
            $nextMonthString = 'month + 1';
        } else {
            $nextYear = $year + 1;
            $nextMonth =  1;

            $nextYearString = 'year + 1';
            $nextMonthString = 'month - 11';
        }

        // Department People Month Info Copy
        $conn = $this->em->getConnection();

        // Month Info
        $query = $this->getCopyQueryForMonthInfo($nextYearString, $nextMonthString);

        $stmt = $conn->prepare($query);

        $params = array(
            ':year' => $year,
            ':month' => $month,
            ':nextYear' => $nextYear,
            ':nextMonth' => $nextMonth,
            ':personId' => $personId,
            ':replacementId' => $replacementId,
        );

        $stmt->execute($params);
        // EOF Month Info

        // Grafik
        $insertGrafikString = $this->generateInsertStringGrafik(
            $personId,
            0,
            $departmentId,
            $year,
            $month,
            $nextYear,
            $nextMonth
        );

        if ($insertGrafikString) {
            // @todo people ids must count from DepartmentPeopleMonthInfo
            $query = $this->getCopyQueryForGrafik();

            $query .= $insertGrafikString;

            $stmt = $conn->prepare($query);

            $stmt->execute(array());
        }

        $insertGrafikTimeString = $this->generateInsertStringGrafikTime(
            $personId,
            $replacementId,
            $departmentId,
            $year,
            $month,
            $nextYear,
            $nextMonth
        );

        if ($insertGrafikTimeString) {
            // Copy grafik time

            // @todo people ids must count from DepartmentPeopleMonthInfo
            $query = $this->getCopyQueryForGrafikTime();

            $query .= $insertGrafikTimeString;

            $stmt = $conn->prepare($query);

            $stmt->execute(array());
        }
    }

    /**
     * Generate insert string for Grafik Model
     *
     * @param int $personId
     * @param int $replacementId
     * @param int $departmentId
     * @param int $year
     * @param int $month
     * @param int $nextYear
     * @param int $nextMonth
     *
     * @return string $return
     */
    public function generateInsertStringGrafik
    (
        $personId,
        $replacementId,
        $departmentId,
        $year,
        $month,
        $nextYear,
        $nextMonth
    // @codingStandardsIgnoreStart
    )
    {
    // @codingStandardsIgnoreEnd
        $workDataPrevMonth = $this->getWorkDataArray(
            'Grafik',
            $personId,
            $replacementId,
            $departmentId,
            $year,
            $month,
            true,
            true
        );

        $workDataNextMonth = $this->getWorkDataArray(
            'Grafik',
            $personId,
            $replacementId,
            $departmentId,
            $nextYear,
            $nextMonth
        );

        $return = array();

        /*'VALUES (
              year,
              month,
              department_id,
              department_people_id,
              department_people_replacement_id,
              day,
              total,
              is_sick,
              is_skip,
              is_fired,
              is_vacation,
              total_day,
              total_evening,
              total_night,
              total_not_officially,
              total_day_not_officially,
              total_evening_not_officially,
              total_night_not_officially)'*/

        $pattern = '(%d, %d, %d, %d, %d, %d, %f, %s, %s, %s, %s, %f, %f, %f, %f, %f, %f, %f)';

        $workDaysInNextMonth = $this->getWorkDaysInTheMonth($nextYear, $nextMonth);

        foreach ($workDaysInNextMonth as $day) {
            /** @var Grafik $prevDayData */
            if (!sizeof($workDataPrevMonth)) {
                break;
            }

            $prevDayData = array_shift($workDataPrevMonth);

            if (!$prevDayData) {
                continue;
            }

            if (isset($workDataNextMonth[$day])) {
                continue;
            }

            $dataTotal= $prevDayData->getTotal();
            $dataTotalDay = $prevDayData->getTotalDay();
            $dataTotalEvening = $prevDayData->getTotalEvening();
            $dataTotalNight = $prevDayData->getTotalNight();
            $dataIsSick = $prevDayData->getIsSick() ? 'true' : 'false';
            $dataIsSkip = $prevDayData->getIsSkip() ? 'true' : 'false';
            $dataIsFired = $prevDayData->getIsFired() ? 'true' : 'false';
            $dataIsVacation = $prevDayData->getIsVacation() ? 'true' : 'false';

            //Not Officially
            $dataTotalNotOfficially= $prevDayData->getTotalNotOfficially();
            $dataTotalDayNotOfficially = $prevDayData->getTotalDayNotOfficially();
            $dataTotalEveningNotOfficially = $prevDayData->getTotalEveningNotOfficially();
            $dataTotalNightNotOfficially = $prevDayData->getTotalNightNotOfficially();

            $return[] = sprintf(
                $pattern,
                $nextYear,
                $nextMonth,
                $departmentId,
                $personId,
                $replacementId,
                $day,
                $dataTotal,
                $dataIsSick,
                $dataIsSkip,
                $dataIsFired,
                $dataIsVacation,
                $dataTotalDay,
                $dataTotalEvening,
                $dataTotalNight,
                $dataTotalNotOfficially,
                $dataTotalDayNotOfficially,
                $dataTotalEveningNotOfficially,
                $dataTotalNightNotOfficially
            );
        }

        if (!sizeof($return)) {
            return '';
        }

        return implode(', ', $return);
    }

    /**
     * Generate insert string for Grafik Model
     *
     * @param int $personId
     * @param int $replacementId
     * @param int $departmentId
     * @param int $year
     * @param int $month
     * @param int $nextYear
     * @param int $nextMonth
     *
     * @return string $return
     */
    public function generateInsertStringGrafikTime
    (
        $personId,
        $replacementId,
        $departmentId,
        $year,
        $month,
        $nextYear,
        $nextMonth
    // @codingStandardsIgnoreStart
    )
    {
    // @codingStandardsIgnoreEnd
        $workDataPrevMonth = $this->getWorkDataArray(
            'GrafikTime',
            $personId,
            $replacementId,
            $departmentId,
            $year,
            $month,
            true,
            true
        );
        $workDataNextMonth = $this->getWorkDataArray(
            'GrafikTime',
            $personId,
            $replacementId,
            $departmentId,
            $nextYear,
            $nextMonth
        );

        $return = array();

        /*'VALUES (
              year,
              month,
              department_id,
              department_people_id,
              department_people_replacement_id,
              day,
              from_time,
              to_time,
              not_official,
              total,
              total_day,
              total_evening,
              total_night,
              total,
              total_day,
              total_evening,
              total_night)'*/

        $pattern = "(%d, %d, %d, %d, %d, %d, '%s', '%s', %s, %f, %f, %f, %f, %f, %f, %f, %f)";

        $workDaysInNextMonth = $this->getWorkDaysInTheMonth($nextYear, $nextMonth);

        foreach ($workDaysInNextMonth as $day) {
            if (!sizeof($workDataPrevMonth)) {
                break;
            }

            $prevDayDataArray = array_shift($workDataPrevMonth);

            if (!sizeof($prevDayDataArray)) {
                continue;
            }

            if (isset($workDataNextMonth[$day])) {
                continue;
            }

            /** @var GrafikTime $prevDayData */
            foreach ($prevDayDataArray as $prevDayData) {
                $dataFromTime = $prevDayData->getFromTime();
                $dataToTime = $prevDayData->getToTime();

                $dataTotal = $prevDayData->getTotal();
                $dataTotalDay = $prevDayData->getTotalDay();
                $dataTotalEvening = $prevDayData->getTotalEvening();
                $dataTotalNight = $prevDayData->getTotalNight();

                //Not Officially
                $dataNotOfficially = $prevDayData->getNotOfficially() ? 'true' : 'false';
                $dataTotalNotOfficially = $prevDayData->getTotalNotOfficially();
                $dataTotalDayNotOfficially = $prevDayData->getTotalDayNotOfficially();
                $dataTotalEveningNotOfficially = $prevDayData->getTotalEveningNotOfficially();
                $dataTotalNightNotOfficially = $prevDayData->getTotalNightNotOfficially();

                $return[] = sprintf(
                    $pattern,
                    $nextYear,
                    $nextMonth,
                    $departmentId,
                    $personId,
                    $replacementId,
                    $day,
                    $dataFromTime ? $dataFromTime->format('Y-m-d H:i:s') : '',
                    $dataToTime ? $dataToTime->format('Y-m-d H:i:s') : '',
                    $dataNotOfficially,
                    $dataTotal,
                    $dataTotalDay,
                    $dataTotalEvening,
                    $dataTotalNight,
                    $dataTotalNotOfficially,
                    $dataTotalDayNotOfficially,
                    $dataTotalEveningNotOfficially,
                    $dataTotalNightNotOfficially
                );
            }
        }

        if (!sizeof($return)) {
            return '';
        }

        return implode(', ', $return);
    }

    /**
     * Get array of working data from grafik or Grafik time
     *
     * @param string $model
     * @param int    $personId
     * @param int    $replacementId
     * @param int    $departmentId
     * @param int    $year
     * @param int    $month
     * @param bool   $withoutWeekends
     * @param bool   $withEmpty
     *
     * @return mixed[]
     */
    public function getWorkDataArray
    (
        $model,
        $personId,
        $replacementId,
        $departmentId,
        $year,
        $month,
        $withoutWeekends = true,
        $withEmpty = false
    // @codingStandardsIgnoreStart
    )
    {
    // @codingStandardsIgnoreEnd
        $workDays = array();

        if ($withoutWeekends) {
            $workDays = $this->getWorkDaysInTheMonth($year, $month);
        }

        $query = $this->em->getRepository('ListsGrafikBundle:' . ucfirst($model))
            ->createQueryBuilder('q')
            ->where('q.departmentPeopleId = :departmentPeopleId')
            ->andWhere('q.departmentPeopleReplacementId = :replacementId')
            ->andWhere('q.departmentId = :departmentId')
            ->andWhere('q.year = :year')
            ->andWhere('q.month = :month')
            ->setParameter(':departmentPeopleId', $personId)
            ->setParameter(':replacementId', $replacementId)
            ->setParameter(':departmentId', $departmentId)
            ->setParameter(':year', $year)
            ->setParameter(':month', $month);


        if ($withoutWeekends) {
            $query
                ->andWhere('q.day in (:workDays)')
                ->setParameter(':workDays', $workDays);
        }

        /** @var Grafik[] $data */
        $data = $query->getQuery()->getResult();

        if (!sizeof($data)) {
            return array();
        }

        $resultArray = array();

        switch (ucfirst($model))
        {
            case 'Grafik':
                foreach ($data as $record) {
                    $resultArray[$record->getDay()] = $record;
                }
                break;
            case 'GrafikTime':
                foreach ($data as $record) {
                    $resultArray[$record->getDay()][] = $record;
                }

                break;
        }

        if ($withEmpty) {
            foreach ($workDays as $day) {
                if (!isset($resultArray[$day])) {
                    $resultArray[$day] = array();
                }
            }

            if (sizeof($resultArray)) {
                ksort($resultArray);
            }
        }

        return $resultArray;
    }

    /**
     * Returns work days in the month
     *
     * @param int $year
     * @param int $month
     *
     * @return int[]
     */
    public function getWorkDaysInTheMonth($year, $month)
    {
        $daysCount = $this->getDaysInTheMonth($year, $month);

        $weekendsAll = $this->getAllWeekends($year, $month);

        $result = array();

        for ($i = 1; $i <= $daysCount; $i++) {
            if (in_array($i, $weekendsAll)) {
                continue;
            }

            $result[] = $i;
        }

        return $result;
    }

    /**
     * @param string $nextYearString
     * @param string $nextMonthString
     *
     * @return string
     */
    public function getCopyQueryForMonthInfo($nextYearString, $nextMonthString)
    {
        return "
         insert
           into department_people_month_info
           (
             year,
             month,
             department_people_id,
             surcharge,
             surcharge_type_id,
             bonus,
             bonus_type_id,
             fine,
             fine_type_id,
             salary,
             position_id,
             type_id,
             type_string,
             employment_type_id,
             salary_type_id,
             is_clean_salary,
             norma_days,
             department_people_replacement_id
           )
           (
             select
               dpmi." . $nextYearString . " as year,
               dpmi." . $nextMonthString . " as month,
               dpmi.department_people_id,
               dpmi.surcharge,
               dpmi.surcharge_type_id,
               dpmi.bonus,
               dpmi.bonus_type_id,
               dpmi.fine,
               dpmi.fine_type_id,
               dpmi.salary,
               dpmi.position_id,
               dpmi.type_id,
               dpmi.type_string,
               dpmi.employment_type_id,
               dpmi.salary_type_id,
               dpmi.is_clean_salary,
               dpmi.norma_days,
               dpmi.department_people_replacement_id
             from
               department_people_month_info dpmi
             where
               dpmi.year = :year and
               dpmi.month = :month and
               dpmi.department_people_id = :personId and
               dpmi.department_people_replacement_id = :replacementId and
               not exists
                 (
                   select
                     1
                   from
                     department_people_month_info dpmi1
                   where
                     dpmi1.year = :nextYear and
                     dpmi1.month = :nextMonth and
                     dpmi1.department_people_id = :personId and
                     dpmi1.department_people_replacement_id = :replacementId
                 )
            )";
    }

    /**
     * @return string
     */
    public function getCopyQueryForGrafik()
    {
        return "
          insert
            into grafik
            (
              year,
              month,
              department_id,
              department_people_id,
              department_people_replacement_id,
              day,
              total,
              is_sick,
              is_skip,
              is_fired,
              is_vacation,
              total_day,
              total_evening,
              total_night,
              total_not_officially,
              total_day_not_officially,
              total_evening_not_officially,
              total_night_not_officially
            )
            VALUES ";
    }

    /**
     * @return string
     */
    public function getCopyQueryForGrafikTime()
    {
        return "
          insert
            into grafik_time
            (
              year,
              month,
              department_id,
              department_people_id,
              department_people_replacement_id,
              day,
              from_time,
              to_time,
              not_officially,
              total,
              total_day,
              total_evening,
              total_night,
              total_not_officially,
              total_day_not_officially,
              total_evening_not_officially,
              total_night_not_officially
            )
            VALUES ";
    }

    /**
     * @param int $departmentId
     * @param int $year
     * @param int $month
     *
     * @return DepartmentPeopleMonthInfo[]|Collection
     */
    public function getPersistentPeople($departmentId, $year, $month)
    {
        /** @var  $monthInfoRepository DepartmentPeopleMonthInfoRepository */
        $monthInfoRepository = $this->em->getRepository('ListsDepartmentBundle:DepartmentPeopleMonthInfo');

        $monthInfo = $monthInfoRepository->createQueryBuilder('dpmi')
            ->where('dp.departmentId = :departmentId')
            ->andWhere('dpmi.year = :year')
            ->andWhere('dpmi.month = :month')
            ->andWhere('dpmi.replacementType = :replacementType')
            ->andWhere('dpmi.replacementId = :replacementId')
            ->leftJoin('dpmi.departmentPeople', 'dp')
            ->setParameter(':departmentId', $departmentId)
            ->setParameter(':year', $year)
            ->setParameter(':month', $month)
            ->setParameter(':replacementType', $monthInfoRepository::REPLACEMENT_TYPE_REPLACEMENT)
            ->setParameter(':replacementId', 0)
            ->getQuery()
            ->getResult();

        return $monthInfo;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return array
     */
    public function getAllWeekends($year, $month)
    {
        $daysCount = date('t', mktime(0, 0, 0, $month, 1, $year));

        $holidays = $this->getHolidays($year, $month);

        for ($day = 1; $day <= $daysCount; $day++) {
            $date = new \DateTime();
            $date->setDate($year, $month, $day);
            $date->format('U');

            $isThisDayIsHoliday = in_array($day, $holidays);
            $isThisDayIsWeekend = in_array($date->format('D'), array('Sun', 'Sat'));

            if ($isThisDayIsWeekend) {
                if (!in_array($day, $holidays)) {
                    $holidays[] = $day;
                }
            }

            // add holiday on monday
            if ($isThisDayIsHoliday && $isThisDayIsWeekend) {
                $newHoliday = null;

                if ($date->format('D') == 'Sat') {
                    $newHoliday = $day + 2;
                }
                if ($date->format('D') == 'Sun') {
                    $newHoliday = $day + 1;
                }

                if (!in_array($newHoliday, $holidays)) {
                    $holidays[] = $newHoliday;
                }
            }
        }

        return $holidays;
    }


    /**
     * @param int $year
     * @param int $month
     *
     * @return mixed[]
     */
    public function getHolidays($year, $month)
    {
        $monthDaysRepository = $this->em->getRepository('ListsGrafikBundle:Salary');

        $monthDay = $monthDaysRepository->findOneBy(array(
            'year' => $year,
            'month' =>$month
        ));

        $holidays = $monthDay ? $monthDay->getWeekends() : array();

        return $holidays ? array_map('intval', explode(',', $holidays)) : array();
    }

    /**
     * @param int   $year
     * @param int   $month
     * @param array $holidays
     *
     * @return int
     */
    public function getFirstWorkDay($year, $month, $holidays = array())
    {
        $daysCount = $this->getDaysInTheMonth($year, $month);

        for ($day = 1; $day <= $daysCount; $day++) {
            if (!$this->isWeekend($year, $month, $day, $holidays)) {
                return $day;
            }
        }

        return 1;
    }

    /**
     * @param int $year
     * @param int $month
     *
     * @return int
     */
    public function getDaysInTheMonth($year, $month)
    {
        return date('t', mktime(0, 0, 0, $month, 1, $year));
    }

    /**
     * @param int   $year
     * @param int   $month
     * @param int   $day
     * @param int[] $holidays
     *
     * @return bool
     */
    public function isWeekend($year, $month, $day, $holidays = array())
    {
        $date = new \DateTime();
        $date->setDate($year, $month, $day);
        $date->format('U');

        if (in_array($day, $holidays)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed[] $options
     *
     * @return bool
     */
    public function deleteUserFromGrafik($options)
    {
        // Department People Month Info Copy
        $conn = $this->em->getConnection();
        //$conn->beginTransaction();

        $params = array(
            ':year' => $options['year'],
            ':month' => $options['month'],
            ':department_id' => $options['departmentId'],
            ':department_people_id' => $options['departmentPeopleId'],
            ':department_people_replacement_id' => $options['replacementId'],
            ':replacement_type' => $options['replacementType'],
        );

        $queryGrafikTime = $this->deleteGrafikTimeDataQuery();
        $stmt = $conn->prepare($queryGrafikTime);
        $stmt->execute($params);

        $queryGrafik= $this->deleteGrafikDataQuery();
        $stmt = $conn->prepare($queryGrafik);
        $stmt->execute($params);

        unset($params[':department_id']);
        $queryMonthInfo= $this->deleteMonthInfoDataQuery();
        $stmt = $conn->prepare($queryMonthInfo);
        $stmt->execute($params);

        return true;
    }

    /**
     * @return string
     */
    public function deleteGrafikDataQuery()
    {
        $query = "
            delete
            from
                grafik
            where
                year = :year and
                month = :month and
                department_people_id = :department_people_id and
                department_people_replacement_id = :department_people_replacement_id and
                department_id = :department_id and
                replacement_type = :replacement_type";

        return $query;
    }

    /**
     * @return string
     */
    public function deleteGrafikTimeDataQuery()
    {
        $query = "
            delete
            from
                grafik_time
            where
                year = :year and
                month = :month and
                department_people_id = :department_people_id and
                department_people_replacement_id = :department_people_replacement_id and
                department_id = :department_id and
                replacement_type = :replacement_type";

        return $query;
    }

    /**
     * @return string
     */
    public function deleteMonthInfoDataQuery()
    {
        $query = "
            delete
            from
                department_people_month_info
            where
                year = :year and
                month = :month and
                department_people_id = :department_people_id and
                department_people_replacement_id = :department_people_replacement_id and
                replacement_type = :replacement_type";

        return $query;
    }
}
