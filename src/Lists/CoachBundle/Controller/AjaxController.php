<?php

namespace Lists\CoachBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lists\CoachBundle\Entity\ActionTopic;
use Lists\CoachBundle\Entity\ActionType;
use Lists\CoachBundle\Entity\CoachRegion;

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
                'value' => $actionTopic->getId(),
                'title' => $actionTopic->getTitle(),
                'text' => $actionTopic->getTitle()
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * Returns action topics by ids
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function actionTopicsByIdsAction(Request $request)
    {
        $ids = explode(',', $request->query->get('id'));

        $actionTopics = $this
            ->getDoctrine()
            ->getRepository('ListsCoachBundle:ActionTopic')
            ->findBy(array('id' => $ids));

        $result = [];
        foreach ($actionTopics as $actionTopic) {
            $result[] = [
                            'id' => $actionTopic->getId(),
                            'value' => $actionTopic->getId(),
                            'title' => $actionTopic->getTitle(),
                            'text' => $actionTopic->getTitle()
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * Adds/edits topic
     * 
     * @param Request $request
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
            $actionTopic = $em
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteTopicAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $actionTopic = $em
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
                'value' => $actionType->getId(),
                'title' => $actionType->getTitle(),
                'text' => $actionType->getTitle()
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * Returns action types by ids
     * 
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function actionTypesByIdsAction(Request $request)
    {
        $ids = explode(',', $request->query->get('id'));

        $actionTypes = $this
            ->getDoctrine()
            ->getRepository('ListsCoachBundle:ActionType')
            ->findBy(array('id' => $ids));

        $result = [];
        foreach ($actionTypes as $actionType) {
            $result[] = [
                'id' => $actionType->getId(),
                'value' => $actionType->getId(),
                'title' => $actionType->getTitle(),
                'text' => $actionType->getTitle()
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * Adds/edits type
     * 
     * @param Request $request
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
            $actionType = $em
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function deleteTypeAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $actionType = $em
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

            $em = $this->getDoctrine()->getManager();
            $coachRegion = $em
                ->getRepository('ListsCoachBundle:CoachRegion')
                ->findOneBy(array('user' => $user));
            if ($coachRegion) {
                $em->remove($coachRegion);
                $em->flush();
            }
        }

        $response = ['success' => $status ? 1 : 0];

        return new JsonResponse($response);
    }

    /**
     * Returns json regions list for coach
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function coachSaveRegionsAction(Request $request)
    {
        $userId = $request->get('pk');
        $values = $request->get('value');
        $em = $this->getDoctrine()->getManager();

        $regions = $em
            ->getRepository('ListsRegionBundle:Region')
            ->findBy(array('id' => $values));

        $user = $em
            ->getRepository('SDUserBundle:User')
            ->find($userId);

        $coachRegion = $em
            ->getRepository('ListsCoachBundle:CoachRegion')
            ->findOneBy(array('user' => $user));

        if (!$coachRegion) {
            $coachRegion = new CoachRegion();
            $coachRegion->setUser($user);
        }

        $coachRegion->setRegions(new \Doctrine\Common\Collections\ArrayCollection($regions));
        try {
            $em->persist($coachRegion);
            $em->flush();
        } catch (\Exception $e) {
            //Some error message...
        }

        return new JsonResponse();
    }

    /**
     * Returns json list of users, authored at least one report
     * 
     * @return JsonResponse
     */
    public function coachListAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $coachReportRepository = $this->getDoctrine()
            ->getRepository('ListsCoachBundle:CoachReport');
        $userRepository = $this->getDoctrine()
            ->getRepository('SDUserBundle:User');

        $coachIds = $coachReportRepository->getAuthors($searchText);

        $i = [];
        foreach ($coachIds as $coachId) {
            $i[] = $coachId['1'];
        }

        $coaches = $userRepository->findBy(array('id' => $i));

        $result = [];
        if ($coaches) {
            foreach ($coaches as $coach) {
                $result[] = array(
                    'id' => $coach->getId(),
                    'value' => $coach->getId(),
                    'name' => $coach->__toString(),
                    'text' => $coach->__toString()
                );
            }
        }

        return new JsonResponse($result);
    }

    /**
     * Returns json list of organizations
     *
     * @return JsonResponse
     */
    public function organizationListAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $coachReportRepository = $this->getDoctrine()
            ->getRepository('ListsCoachBundle:CoachReport');
        $organizationRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');

        $organizationIds = $coachReportRepository->getOrganizations($searchText);

        $i = [];
        foreach ($organizationIds as $organizationId) {
            $i[] = $organizationId['1'];
        }

        $organizations = $organizationRepository->findBy(array('id' => $i));

        $result = [];
        if ($organizations) {
            foreach ($organizations as $organization) {
                $result[] = array(
                                'id' => $organization->getId(),
                                'value' => $organization->getId(),
                                'name' => $organization->getName(),
                                'text' => $organization->getName()
                );
            }
        }

        return new JsonResponse($result);
    }

    /**
     * Returns json list of cities
     *
     * @return JsonResponse
     */
    public function cityListAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $coachReportRepository = $this->getDoctrine()
            ->getRepository('ListsCoachBundle:CoachReport');
        $cityRepository = $this->getDoctrine()
            ->getRepository('ListsCityBundle:City');

        $cityIds = $coachReportRepository->getCities($searchText);

        $i = [];
        foreach ($cityIds as $cityId) {
            $i[] = $cityId['1'];
        }

        $cities = $cityRepository->findBy(array('id' => $i));

        $result = [];
        if ($cities) {
            foreach ($cities as $city) {
                $result[] = array(
                                'id' => $city->getId(),
                                'value' => $city->getId(),
                                'name' => $city->getName(),
                                'text' => $city->getName()
                );
            }
        }

        return new JsonResponse($result);
    }

    /**
     * Returns json list of members
     *
     * @return JsonResponse
     */
    public function membersListAction()
    {
        $searchText = $this->get('request')->query->get('query');

        $indRepository = $this->getDoctrine()
            ->getRepository('ListsIndividualBundle:Individual');

        $inds = $indRepository->getMembers($searchText);

        $result = [];
        if ($inds) {
            foreach ($inds as $ind) {
                $result[] = array(
                                'id' => $ind->getId(),
                                'value' => $ind->getId(),
                                'name' => $ind->__toString(),
                                'text' => $ind->__toString()
                );
            }
        }

        return new JsonResponse($result);
    }

    /**
     * Returns json list of departments
     *
     * @return JsonResponse
     */
    public function departmentsByCityIdAction()
    {
        $searchText = $this->get('request')->query->get('query');
        $cityId = intval($this->get('request')->query->get('dependent'));

        $coachReportRepository = $this->getDoctrine()
            ->getRepository('ListsCoachBundle:CoachReport');
        $depRepository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $depIds = $coachReportRepository->getDepartmentsByCityId($searchText, $cityId);

        $i = [];
        foreach ($depIds as $depId) {
            $i[] = $depId['1'];
        }

        $deps = $depRepository->findBy(array('id' => $i));

        $result = [];
        if ($deps) {
            foreach ($deps as $dep) {
                $result[] = array(
                                'id' => $dep->getId(),
                                'value' => $dep->getId(),
                                'name' => $dep->getName(),
                                'text' => $dep->getName()
                );
            }
        }

        return new JsonResponse($result);
    }
}
