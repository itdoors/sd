<?php

namespace SD\UserBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * UserStatisticController
 */
class UserStatisticController extends BaseController
{

    protected $baseTemplate = 'User';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

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
        $pdo = $this->container->get('session.handler.pdo');
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

                    $pdo->destroy($userLoginRecord->getSessionId());
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
                        'baseTemplate' => 'User',
                        'namespase' => 'stuffFilterForm'
        ));
    }

    /**
     * timeOnlineListAction
     *
     * @return string
     */
    public function timeOnlineListAction()
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $status = $em->getRepository('ListsLookupBundle:Lookup')
            ->findOneBy(array('lukey' => 'worked'));
            $filters['status'] = $status->getId();
            $this->setFilters($namespase, $filters);
        }
        $users = $this->get('sd_user.repository')->getAllForUserQuery($filters);
        $entities = $users['entity'];
        $count = $users['count'];

        $page = $this->getPaginator($namespase);
        if (!$page) {
            $page = 1;
        }

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 10);

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':timeOnlineList.html.twig', array(
                        'namespase' => $namespase,
                        'items' => $pagination,
                        'baseTemplate' => $this->baseTemplate
        ));
    }
}
