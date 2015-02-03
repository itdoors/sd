<?php

namespace Lists\GrafikBundle\Classes;

/**
 * BankDay class
 */
abstract class BankDay
{
    /**
     * $holidays
     * 
     * @example
     * 
     * $grafikService = $this->container->get('lists_grafik.service');
     * 
     * загружаем праздничные дни из базы
     * $grafikService->loadHoliday();
     * 
     * получаем дату от текущей через 5 рабочих дней
     * $data = BankDay::getEndDate(time(), 5, 'Y-m-d')
     */
    protected static $holidays = array (
        '2015-01-01',
        '2015-01-02',
        '2015-01-03',
        '2015-01-04',
        '2015-01-05',
        '2015-01-07',
        '2015-02-23',
        '2015-03-08',
        '2015-05-01',
        '2015-05-09'
    );
    # 1, 2, 3, 4 и 5 января - Новогодние каникулы;
    # 7 января - Рождество Христово;
    # 23 февраля - День защитника Отечества;
    # 8 марта - Международный женский день;
    # 1 мая - Праздник Весны и Труда;
    # 9 мая - День Победы;
    protected static $weekends = array (0, 6);

    # 0 - Воскресенье
    # 6 - Суббота
    
    /**
     * устанавливаем праздники
     * 
     * @param mixaed[] $dates array('Y-m-d')
     */
    public static function setHoliday ($dates)
    {
        self::$holidays = $dates;
    }
    /**
     * 
     * @param string $s 'Y-m-d' or time()
     * 
     * @return timestap
     * @throws Exception
     */
    public static function prepareDate ($s)
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
    public static function isWeekend ($date)
    {
        $ts = self::prepareDate($date);
        return in_array(date('w', $ts), self::$weekends);
    }
    /**
     * Определяет праздничный ли день
     * 
     * @param string $date 'Y-m-d' or timestap
     * 
     * @return boolean
     */
    public static function isHoliday ($date)
    {
        $ts = self::prepareDate($date);
        return in_array(date('Y-m-d', $ts), self::$holidays);
    }
    /**
     * Определяет рабочий ли день
     * 
     * @param string $date 'Y-m-d' or timestap
     * 
     * @return boolean
     */
    public static function isWorkDay ($date)
    {
        $ts = self::prepareDate($date);
        $holidays = self::getHolidays($ts);
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
    public static function getHolidays ($date, $interval = 30)
    {
        $ts = self::prepareDate($date);
        $holidays = array ();

        for ($i = -$interval; $i <= $interval; $i++) {
            $curr = strtotime($i . ' days', $ts);

            if (self::isWeekend($curr) || self::isHoliday($curr)) {
                $holidays[] = date('Y-m-d', $curr);
            }
        }

        // Перенос праздников
        foreach ($holidays as $date) {
            $ts = self::prepareDate($date);
            if (self::isHoliday($ts) && self::isWeekend($ts)) {
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
    public static function getEndDate ($start, $days, $format = null)
    {
        $ts = self::prepareDate($start);
        $holidays = self::getHolidays($start);

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
    public static function getNumDays ($starDate, $endDate)
    {
        $start = self::prepareDate($starDate);
        $end = self::prepareDate($endDate);

        if ($start > $end) {
            throw new Exception(sprintf('Start date ("%s") bust be greater then end date ("%s"). ', $starDate, $endDate));
        }

        $bank_days = 0;
        $days = ceil(($end - $start) / 3600 / 24);
        $holidays = self::getHolidays($start, $days);
        for ($i = 0; $i <= $days; $i++) {
            $curr = strtotime('+' . $i . ' days', $start);
            if (!in_array(date('Y-m-d', $curr), $holidays)) {
                $bank_days++;
            }
        }

        return $bank_days;
    }
}
