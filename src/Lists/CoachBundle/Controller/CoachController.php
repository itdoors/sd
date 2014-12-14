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

        $userData = [];
        foreach ($users as $user) {
            $coachRegion = $this->getDoctrine()
                ->getRepository('ListsCoachBundle:CoachRegion')
                ->findOneBy(array('user' => $user));

            if ($coachRegion) {
                $regionList = $coachRegion->getRegions();
            } else {
                $regionList = [];
            }

            $result = [];
            $result['values'] = '';
            $result['text'] = '';

            foreach ($regionList as $region) {
                if ($result['values'] == '') {
                    $result['values'] .= $region->getId();
                } else {
                    $result['values'] .= ',' . $region->getId();
                }
                if ($result['text'] == '') {
                    $result['text'] .= $region->getName();
                } else {
                    $result['text'] .= ', ' . $region->getName();
                }
            }

            $userData[] = [
                'user' => $user,
                'regions' => $result
            ];
        }

        return $this->render('ListsCoachBundle:Coach:list.html.twig', array(
                        'userData' => $userData
        ));
    }
}
