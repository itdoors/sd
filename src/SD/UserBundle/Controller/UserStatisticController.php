<?php

namespace SD\UserBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * UserStatisticController
 */
class UserStatisticController extends BaseController
{

    protected $baseTemplate = 'User';

    /**
     * Executes showLoginHistory action
     *
     * @param integer $id
     *
     * @return string
     */
    public function showLoginHistoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('SDUserBundle:User')->find($id);
        $userLoginRecords = $em->getRepository('SDUserBundle:UserLoginRecord')->findBy(
            array('user' => $user),
            array('logedIn' => 'DESC'),
            100 //limit
        );

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':loginHistoryList.html.twig', array(
                'items' => $userLoginRecords,
                'currentIp' => $this->getRequest()->getClientIp()
        ));
    }

    /**
     * Executes activeUsers action
     *
     * @return string
     */
    public function activeUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userLoginRecordRepository = $this->getDoctrine()->getRepository('SDUserBundle:UserLoginRecord');
        $userActivityRecords = $this->getDoctrine()->getRepository('SDUserBundle:UserActivityRecord')->findAll();

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':activeUsers.html.twig', array(
                        'items' => $userActivityRecords
        ));
    }

    /**
     * Executes kill action
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function killAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('SDUserBundle:User');
        $userLoginRecordsRepository = $em->getRepository('SDUserBundle:UserLoginRecord');
        $userActivityRecordsRepository = $em->getRepository('SDUserBundle:UserActivityRecord');

        $users = [];
        $em->getConnection()->beginTransaction();
        try {
            foreach ($request->get('users') as $userId) {
                $user = $userRepository->find($userId);
                $userActivityRecord = $userActivityRecordsRepository->findOneBy(array(
                            'user' => $user
                ));
                $userLoginRecords = $userLoginRecordsRepository->findBy(array(
                            'user' => $user,
                            'logedOut' => null
                ));
                foreach ($userLoginRecords as $userLoginRecord) {
                    $userLoginRecord->setLogedOut(new \DateTime("now"));
                    $em->merge($userLoginRecord);

                    $sql = 'DELETE FROM session WHERE session_id =:session_id';
                    $em->getConnection()->prepare($sql)->execute(array(
                                    'session_id' => $userLoginRecord->getSessionId()
                    ));
                }
                $em->remove($userActivityRecord);
                $em->flush();
                $em->getConnection()->commit();
            }
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            throw $e;
        }

        return new Response();
    }

    /**
     * Executes keepAlive action
     *
     * @return string
     */
    public function keepAliveAction()
    {
        return new Response('OK');
    }

    /**
     * Executes timeOnline action
     *
     * @return string
     */
    public function timeOnlineAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userLoginRecordRepository = $this->getDoctrine()->getRepository('SDUserBundle:UserLoginRecord');
        $userActivityRecords = $this->getDoctrine()->getRepository('SDUserBundle:UserActivityRecord')->findAll();

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':timeOnline.html.twig', array(
        ));
    }
}
