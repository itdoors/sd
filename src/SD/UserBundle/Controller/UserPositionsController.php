<?php

namespace SD\UserBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * UserStatisticController
 */
class UserPositionsController extends BaseController
{
    /**
     * Executes index action
     *
     * @return string
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $positions = $this->getDoctrine()
                           ->getRepository('SDUserBundle:Position')
                           ->findBy(array(), array('name' => 'ASC'));

        return $this->render('SDUserBundle:Positions:index.html.twig', array(
                        'positions' => $positions
        ));
    }

    /**
     * Executes saveGroups action
     * 
     * @param Request $request
     *
     * @return string
     */
    public function saveGroupsAction(Request $request)
    {
        $positionId = $request->get('pk');
        $values = $request->get('value');
        $em = $this->getDoctrine()->getManager();

        $groups = $this->getDoctrine()
            ->getRepository('SDUserBundle:Group')
            ->findBy(array('id' => $values));

        $position = $em
            ->getRepository('SDUserBundle:Position')
            ->find($positionId);

        $position->setGroups(new \Doctrine\Common\Collections\ArrayCollection($groups));

        try {
            $em->persist($position);
            $em->flush();
        } catch (\Exception $e) {
            //Some error message...
        }

        return new JsonResponse();
    }

    /**
     * Returns json group name by id
     *
     * @return JsonResponse
     */
    public function groupsByIdsAction()
    {
        $ids = explode(',', $this->get('request')->query->get('id'));

        $groups = $this->getDoctrine()
            ->getRepository('SDUserBundle:Group')
            ->findBy(array('id' => $ids));

        $result = [];
        foreach ($groups as $group) {
            $result[] = array(
                'id' => $group->getId(),
                'value' => $group->getId(),
                'name' => $group->getName(),
                'text' => $group->getName()
            );
        }

        return new JsonResponse($result);
    }

    /**
     * Returns json groups
     *
     * @return JsonResponse
     */
    public function groupsAction()
    {
        $groups = $this->getDoctrine()
            ->getRepository('SDUserBundle:Group')
            ->findAll();

        $result = [];
        foreach ($groups as $group) {
            $result[] = array(
                'id' => $group->getId(),
                'value' => $group->getId(),
                'name' => $group->getName(),
                'text' => $group->getName()
            );
        }

        return new JsonResponse($result);
    }
}
