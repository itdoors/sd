<?php

namespace SD\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SalesAdminController
 */
class SalesAdminController extends SalesDispatcherController
{
    protected $baseRoutePrefix = 'sales_admin';
    protected $baseTemplate = 'SalesAdmin';

    /**
     * Renders calendar for handlingMessage
     *
     * @param Request $request
     *
     * @return string
     */
    public function handlingMessageAction(Request $request)
    {
        $startTimestamp = $request->query->get('start');
        $endTimestamp = $request->query->get('end');

        $events = $this->getEventsByUserIds(null, $startTimestamp, $endTimestamp);

        $response = new JsonResponse($events);

        return $response;
    }
}
