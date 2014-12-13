<?php

namespace Lists\CoachBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lists\CoachBundle\Entity\ActionTopic;
use Lists\CoachBundle\Entity\ActionType;

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
     * Adds/edits topic
     *
     * @return JsonResponse
     */
    public function editTopicAction(Request $request)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $em = $this->getDoctrine()->getManager();
        $actionTopic = null;

        if ($id) {
            $actionTopic = $this
                ->getDoctrine()
                ->getRepository('ListsCoachBundle:ActionTopic')
                ->find($id);
        } else {
            $actionTopic = new ActionTopic();
        }

        $actionTopic->setTitle($title);
        $em->persist($actionTopic);
        $em->flush();

        $response = [
                        'id' => $actionTopic->getId(),
                        'title' => $actionTopic->getTitle()
        ];

        return new JsonResponse($response);
    }

    /**
     * Delete topic
     *
     * @return JsonResponse
     */
    public function deleteTopicAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $actionTopic = $this
            ->getDoctrine()
            ->getRepository('ListsCoachBundle:ActionTopic')
            ->find($id);

        $em->remove($actionTopic);
        $em->flush();

        return new JsonResponse();
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

    /**
     * Adds/edits type
     *
     * @return JsonResponse
     */
    public function editTypeAction(Request $request)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $em = $this->getDoctrine()->getManager();
        $actionType = null;

        if ($id) {
            $actionType = $this
                ->getDoctrine()
                ->getRepository('ListsCoachBundle:ActionType')
                ->find($id);
        } else {
            $actionType = new ActionType();
        }

        $actionType->setTitle($title);
        $em->persist($actionType);
        $em->flush();

        $response = [
                        'id' => $actionType->getId(),
                        'title' => $actionType->getTitle()
        ];

        return new JsonResponse($response);
    }

    /**
     * Delete type
     *
     * @return JsonResponse
     */
    public function deleteTypeAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $actionType = $this
            ->getDoctrine()
            ->getRepository('ListsCoachBundle:ActionType')
            ->find($id);

        $em->remove($actionType);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Sets coach status to user
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function setCoachStatusAction(Request $request)
    {
        $username = $request->get('pk');
        $status = ($request->get('value') == 1);

        /** @var \FOS\UserBundle\Model\UserManager $um */
        $um = $this->get('fos_user.user_manager');

        /** @var \FOS\UserBundle\Model\GroupManager $gm */
        $gm = $this->get('fos_user.group_manager');

        $user = $um->findUserByUsername($username);
        $group = $gm->findGroupByName('COACH');

        if ($status && !$user->hasRole('ROLE_COACH')) {
            $user->addGroup($group);
            $um->updateUser($user);
        } elseif (!$status && $user->hasRole('ROLE_COACH')) {
            $user->removeGroup($group);
            $um->updateUser($user);
        }

        $response = ['success' => $status ? 1 : 0];

        return new JsonResponse($response);
    }
}
