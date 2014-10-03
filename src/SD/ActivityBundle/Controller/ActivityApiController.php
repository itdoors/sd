<?php

namespace SD\ActivityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ActivityApiController
 */
class ActivityApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getActivityAction()
    {
        $activities = $this->get('activity.task.service')->getActivity();

        return new JsonResponse($activities);
    }
}
