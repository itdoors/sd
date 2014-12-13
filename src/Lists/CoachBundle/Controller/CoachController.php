<?php

namespace Lists\CoachBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * CoachController
 */
class CoachController extends Controller
{
    /**
     * @return Response
     */
    public function coachListAction()
    {
        /** @var \FOS\UserBundle\Model\GroupManager $gm */
        $gm = $this->get('fos_user.group_manager');

        $group = $gm->findGroupByName('COACH');
        $users = $group->getUsers();

        return $this->render('ListsCoachBundle:Coach:list.html.twig', array(
                        'items' => $users
        ));
    }
}
