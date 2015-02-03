<?php

namespace Lists\GrafikBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Lists\GrafikBundle\Entity\Salary;

/**
 * GrafikService class
 */
class GrafikService
{
    /**
     * $holidays
     * 
     * @example
     * 
     * $grafikService = $this->container->get('lists_grafik.service');
     * 
     * получаем дату от текущей через 5 рабочих дней в указаном формате
     * $data = $grafikService->getEndDate(time(), 5, 'Y-m-d')
     */
    protected $holidays = array ();
    protected $weekends = array (0, 6);

    # 0 - Воскресенье
    # 6 - Суббота
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->loadHoliday();
    }
    /**
     * loadHoliday
     */
    public function loadHoliday ()
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        /** @var Salary $salaryRepository */
        $salaries = $em->getRepository('ListsGrafikBundle:Salary')->findAll();

        if ($salaries) {
            foreach ($salaries as $salary) {
                $days = explode(',', $salary->getWeekends());
                foreach ($days as $day) {
                    if (!empty($day)) {
                        $date = strtotime($salary->getYear().'-'.$salary->getMonth().'-'.$day);
                        $this->holidays[] = date('Y-m-d' ,$date);
                    }
                }
            }
        }
    }
    /**
     * 
     * @param string $s 'Y-m-d' or time()
     * 
     * @return timestap
     * @throws Exception
     */
    private function prepareDate ($s)
    {
        if ($s !== null && !is_int($s)) {
            $ts = strtotime($s);
            if ($ts === -1 || $ts === false) {
                throw new Exception('Unable to parse date/time value from input: ' . var_export($s, true));
            }
        } else {
            $ts = $s;
        }
        return $ts;
    }
    /**
     * Определяет выходной ли день
     * 
     * @param string $date 'Y-m-d' or timestap
     * 
     * @return boolean
     */
    public function isWeekend ($date)
    {
        $ts = $this->prepareDate($date);

        return in_array(date('w', $ts), $this->weekends);
    }
    /**
     * Определяет праздничный ли день
     * 
     * @param string $date 'Y-m-d' or timestap
     * 
     * @return boolean
     */
    public function isHoliday ($date)
    {
        $ts = $this->prepareDate($date);

        return in_array(date('Y-m-d', $ts), $this->holidays);
    }
    /**
     * Определяет рабочий ли день
     * 
     * @param string $date 'Y-m-d' or timestap
     * 
     * @return boolean
     */
    public function isWorkDay ($date)
    {
        $ts = $this->prepareDate($date);
        $holidays = $this->getHolidays($ts);

        return !in_array(date('Y-m-d', $ts), $holidays);
    }
    /**
     * Возвращает массив выходных дней с учетом праздников
     * 
     * @param string $date 'Y-m-d' or timestap
     * @param integer $interval Интервал (дней)
     *
     * @return array
     */
    public function getHolidays ($date, $interval = 30)
    {
        $ts = $this->prepareDate($date);
        $holidays = array ();

        for ($i = -$interval; $i <= $interval; $i++) {
            $curr = strtotime($i . ' days', $ts);

            if ($this->isWeekend($curr) || $this->isHoliday($curr)) {
                $holidays[] = date('Y-m-d', $curr);
            }
        }

        // Перенос праздников
        foreach ($holidays as $date) {
            $ts = $this->prepareDate($date);
            if ($this->isHoliday($ts) && $this->isWeekend($ts)) {
                $i = 0;
                while (in_array(date('Y-m-d', strtotime($i . ' days', $ts)), $holidays)) {
                    $i++;
                }
                $holidays[] = date('Y-m-d', strtotime($i . ' days', $ts));
            }
        }

        return $holidays;
    }
    /**
     * Возвращает дату +$days банковских дней
     * 
     * @param string  $start  'Y-m-d' or timestap
     * @param integer $days   Кол-во банковских дней
     * @param string  $format Формат date()
     *
     * @return integer|string
     */
    public function getEndDate ($start, $days, $format = null)
    {
        $ts = $this->prepareDate($start);
        $holidays = $this->getHolidays($start);

        for ($i = 0; $i <= $days; $i++) {
            $curr = strtotime('+' . $i . ' days', $ts);
            if (in_array(date('Y-m-d', $curr), $holidays)) {
                $days++;
            }
        }

        if ($format) {
            return date($format, strtotime('+' . $days . ' days', $ts));
        } else {
            return strtotime('+' . $days . ' days', $ts);
        }
    }
    /**
     * Возвращает кол-во банковских дней заданном периоде
     *
     * @param string $starDate 'Y-m-d' or timestap
     * @param string $endDate  'Y-m-d' or timestap
     *
     * @return integer
     */
    public function getNumDays ($starDate, $endDate)
    {
        $start = $this->prepareDate($starDate);
        $end = $this->prepareDate($endDate);

        if ($start > $end) {
            throw new Exception(sprintf('Start date ("%s") bust be greater then end date ("%s"). ', $starDate, $endDate));
        }

        $bank_days = 0;
        $days = ceil(($end - $start) / 3600 / 24);
        $holidays = $this->getHolidays($start, $days);
        for ($i = 0; $i <= $days; $i++) {
            $curr = strtotime('+' . $i . ' days', $start);
            if (!in_array(date('Y-m-d', $curr), $holidays)) {
                $bank_days++;
            }
        }

        return $bank_days;
    }
    public function getHoliday ()
    {
        return $this->holidays;
    }
}
