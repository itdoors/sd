<?php

namespace SD\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesController extends Controller
{
    public function indexAction()
    {
        return $this->render('SDCalendarBundle:Sales:index.html.twig');
    }

    public function handlingMessageAction()
    {
        $user = $this->getUser();

        $userIds = array($user->getId());

        $handlingMessages = $this->getDoctrine()->getRepository('ListsHandlingBundle:HandlingMessage')
            ->getFutureMessages($userIds);

        $events = array();

        foreach ($handlingMessages as $handlingMessage)
        {
            $start = $handlingMessage->getCreatedate();

            $className = $handlingMessage->getType() ? 'calendar-event-' . $handlingMessage->getType()->getSlug() : '';

            $events[] = array(
                'title' => (string) $handlingMessage->getType() . ' (' . $start->format('H:i').')',
                'start' => $start->format('Y-m-d H:i:s'),
                'url' => $this->generateUrl('lists_sales_handling_show', array(
                        'id' => $handlingMessage->getHandlingId()
                    )),
                'className' => $className
           );
        }

        return $this->render('SDCalendarBundle::base.html.twig', array(
            'events' => $events
        ));
    }
}
