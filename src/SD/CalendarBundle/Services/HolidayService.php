<?php

namespace SD\CalendarBundle\Services;

use Symfony\Component\DependencyInjection\Container;

/**
 * HolidayService class
 */
class HolidayService
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


    public function sendEmailToStuff()
    {
        $week = date('w');
        if ($week === 1) {
            $this->sendEmailHoliday('week');
        }
        if (!in_array($week, array(0,6))) {
            $this->sendEmailHoliday('day');
        }
    }
    /**
     * 
     * @param string $period
     */
    public function sendEmailHoliday($period)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $translator = $this->container->get('translator');

        $subject = $translator->trans('Events for '.$period, array(), 'SDDashboardBundle');

        $startTimestamp = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        if ($period == 'week') {
            $endTimestamp = mktime(0, 0, 0, date('m'), date('d')+5, date('Y'));
        } else {
            $endTimestamp = mktime(0, 0, 0, date('m'), date('d')+30, date('Y'));
        }

        $text = $this->getBirthdays($startTimestamp, $endTimestamp);
        $text .= $this->getHolidays($startTimestamp, $endTimestamp);

        echo $text;
        $email = $this->container->get('it_doors_email.service');

        $users = $em->getRepository('SDUserBundle:User')
                ->getBirthdaysForCalendar();
        foreach ($users as $user) {
            if ($user->getEmail()) {
                $email->send(
                    null,
                    'empty-template',
                        array(
                            'users' => array(
                                $user->getEmail()
                            ),
                            'variables' => array(
                                '${subject}$' => $subject,
                                '${text}$' => $text
                            )
                        )
                    );
            } else {
                echo $user.' email not found'."\n";
            }
        }
    }
    /**
     * getBirthdays
     * 
     * @return string
     */
    public function getBirthdays($startTimestamp, $endTimestamp)
    {
       
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $templating = $this->container->get('templating');

        $users = $em->getRepository('SDUserBundle:User')
                ->getBirthdaysForCalendar($startTimestamp, $endTimestamp);
 
        if (count($users) > 0) {
            $html = $templating->render(
                'SDCalendarBundle:Holiday:listBirthdayForEmail.html.twig',
                array('users' => $users)
            );
        } else {
            $html = null;
        }
        

        return $html;
    }
    /**
     * getBirthdays
     * 
     * @return string
     */
    public function getHolidays($startTimestamp, $endTimestamp)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $templating = $this->container->get('templating');

        $holidays = $em->getRepository('SDCalendarBundle:Holiday')
                ->getListForDate($startTimestamp, $endTimestamp);
        if (count($holidays) > 0) {
            $html = $templating->render(
                'SDCalendarBundle:Holiday:listHolidaysForEmail.html.twig',
                array('holidays' => $holidays)
            );
        } else {
            $html = null;
        }
        

        return $html;
    }
}
