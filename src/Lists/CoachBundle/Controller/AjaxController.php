<?php

namespace Lists\CoachBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * AjaxController
 */
class AjaxController extends BaseController
{
    /**
     * Returns all possible action topics
     *
     * @return JsonResponse
     */
    public function actionTopicsAction()
    {
        $actionTopics = $this
            ->getDoctrine()
            ->getRepository('ListsCoachBundle:ActionTopic')
            ->findAll();

        $result = [];
        foreach ($actionTopics as $actionTopic) {
            $result[] = [
                'id' => $actionTopic->getId(),
                'title' => $actionTopic->getTitle()
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * Returns all possible action types
     *
     * @return JsonResponse
     */
    public function actionTypesAction()
    {
        $actionTypes = $this
            ->getDoctrine()
            ->getRepository('ListsCoachBundle:ActionType')
            ->findAll();

        $result = [];
        foreach ($actionTypes as $actionType) {
            $result[] = [
                'id' => $actionType->getId(),
                'title' => $actionType->getTitle()
            ];
        }

        return new JsonResponse($result);
    }
}
