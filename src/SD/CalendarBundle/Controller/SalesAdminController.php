<?php

namespace SD\CalendarBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Class SalesAdminController
 */
class SalesAdminController extends SalesDispatcherController
{
	protected $baseRoutePrefix = 'sales_admin';
	protected $baseTemplate = 'SalesAdmin';

	/**
	 * Renders calendar for handlingMessage
     */
    public function handlingMessageAction(Request $request)
	{
        $startTimestamp = $request->query->get('start');
        $endTimestamp = $request->query->get('end');

        $filters = $this->getFilters($this->container->getParameter('ajax.filter.namespace.dashboard.calendar'));

        $events = $this->getEventsByUserIds(null, $startTimestamp, $endTimestamp, $filters);

        $response = new JsonResponse($events);

        return $response;
	}
}
