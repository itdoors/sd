<?php

namespace SD\ActivityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function getActivityAction()
    {
        $activities = $this->get('activity.organization.service')->getActivities();
        return new JsonResponse(array('activities' => $activities));
    }
}
