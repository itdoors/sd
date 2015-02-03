<?php

namespace Lists\GrafikBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Lists\GrafikBundle\Entity\Salary;
use Lists\GrafikBundle\Classes\BankDay;

/**
 * GrafikService class
 */
class GrafikService
{
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
        $dates = array();
        if ($salaries) {
            foreach ($salaries as $salary) {
                $days = explode(',', $salary->getWeekends());
                foreach ($days as $day) {
                    if (!empty($day)) {
                        $date = strtotime($salary->getYear().'-'.$salary->getMonth().'-'.$day);
                        $dates[] = date('Y-m-d' ,$date);
                    }
                }
            }
        }
        BankDay::setHoliday($dates);
    }
}
