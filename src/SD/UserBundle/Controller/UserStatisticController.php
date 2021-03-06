<?php

namespace SD\UserBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * UserStatisticController
 */
class UserStatisticController extends BaseController
{

    protected $baseTemplate = 'User';
    protected $filterNamespace = 'stuffFilterForm';

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

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':timeOnline.html.twig', array(
                        'baseTemplate' => 'User',
                        'namespase' => $this->filterNamespace
        ));
    }

    /**
     * Executes timeOnlineList action
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

    /**
     * Executes statisticForUsers action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function statisticForUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('SDUserBundle:User');

        $userStatistic = [];
        $end = (new \DateTime)->setTimestamp($request->get('end'));
        $start = (new \DateTime)->setTimestamp($request->get('start'));

        $users = $request->get('users');
        if ($users) {
            foreach ($users as $userId) {
                $qb = $em->createQueryBuilder();
                $totalLogedTime  = $qb
                    ->select('SUM(TIMESTAMPDIFF(MINUTE, ulr.logedIn, ulr.logedOut)) as online')
                    ->addSelect('TIMESTAMPDIFF(MINUTE, MIN(ulr.logedIn), MAX(ulr.logedOut)) as total')
                    ->from('SDUserBundle:UserLoginRecord', 'ulr')
                    ->where($qb->expr()->eq('ulr.user', $userId['id']))
                    ->andWhere($qb->expr()->gt('ulr.logedIn', ':start'))
                    ->andWhere($qb->expr()->lt('ulr.logedOut', ':end'))
                    ->setParameters(array(
                                    'start' => $start,
                                    'end'     => $end
                    ))
                    ->getQuery()
                    ->getResult();

                $user = $userRepository->find($userId);
                $userStatistic[] = [
                                'id' => $user->getId(),
                                'name' => $user->__toString(),
                                'online' => $totalLogedTime[0]['online'],
                                'total' => $totalLogedTime[0]['total']
                ];
            }
        }

        return new Response(json_encode($userStatistic));
    }

    /**
     * Executes findInactiveUsers action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function findInactiveUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('SDUserBundle:User');

        $userStatistic = [];
        $end = (new \DateTime)->setTimestamp($request->get('end'));
        $start = (new \DateTime)->setTimestamp($request->get('start'));

        $qb = $em->createQueryBuilder();

        $nots = $em->createQueryBuilder();
        $nots->select('DISTINCT u.id')
            ->from('SDUserBundle:UserLoginRecord', 'ulr')
            ->leftJoin('ulr.user', 'u')
            ->where($qb->expr()->gt('ulr.logedIn', ':start'))
            ->andWhere($qb->expr()->lt('ulr.logedOut', ':end'));

        $inactiveUsers  = $qb
            ->select('partial user.{id, firstName, lastName}')
            ->from('SDUserBundle:User', 'user')
            ->join('user.stuff', 'stuff')
            ->where($qb->expr()->notIn('user.id', $nots->getDQL()))
            ->setParameters(array(
                'start' => $start,
                'end' => $end
            ))
            ->getQuery()
            ->getArrayResult();

        foreach ($inactiveUsers as $inactiveUser) {
            $userStatistic[] = [
                            'id' => $inactiveUser['id'],
                            'name' => $inactiveUser['lastName'] . " " . $inactiveUser['firstName'],
                            'online' => 0,
                            'total' => 0
            ];
        }

        return new Response(json_encode($userStatistic));
    }

    /**
     * Executes downloadStatistic action
     * 
     * @param Request $request
     *
     * @return string
     */
    public function downloadStatisticAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('SDUserBundle:User');

        $userStatistic = [];
        $end = (new \DateTime)->setTimestamp($request->get('end'));
        $start = (new \DateTime)->setTimestamp($request->get('start'));

        $users = explode(',', $request->get('users'));
        if ($users) {
            foreach ($users as $userId) {
                $qb = $em->createQueryBuilder();
                $totalLogedTime  = $qb
                    ->select('SUM(TIMESTAMPDIFF(MINUTE, ulr.logedIn, ulr.logedOut)) as online')
                    ->addSelect('COUNT(ulr.logedIn) as total')
                    ->from('SDUserBundle:UserLoginRecord', 'ulr')
                    ->where($qb->expr()->eq('ulr.user', $userId))
                    ->andWhere($qb->expr()->gt('ulr.logedIn', ':start'))
                    ->andWhere($qb->expr()->lt('ulr.logedOut', ':end'))
                    ->setParameters(array(
                                    'start' => $start,
                                    'end'     => $end
                    ))
                    ->getQuery()
                    ->getResult();

                $user = $userRepository->find($userId);
                $userStatistic[] = [
                                'id' => $user->getId(),
                                'name' => $user->__toString(),
                                'online' => $totalLogedTime[0]['online'],
                                'total' => $totalLogedTime[0]['total']
                ];
            }
        }

        ini_set("max_execution_time", "180");
        $transNamespace = 'SDUserBundle';
        $translator = $this->container->get('translator');
        $phpExcelObject = $this->container->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()
            ->setCreator("Supervisor")
            ->setLastModifiedBy("Supervisor")
            ->setTitle("Departments")
            ->setSubject("Departments")
            ->setDescription("Departments")
            ->setKeywords("Departments")
            ->setCategory("Departments");
        $phpExcelObject->setActiveSheetIndex(0);

        $str = 1;
        $col = 0;

        $transHeader = $translator->trans('First name', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Online', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);
        $transHeader = $translator->trans('Logins total', array(), $transNamespace);
        $phpExcelObject->getActiveSheet()
            ->setCellValueByColumnAndRow($col++, $str, $transHeader);

        foreach ($userStatistic as $record) {
            $col = 0;
            ++$str;

            $time = $record['online'];
            $timeString = intval($time/60) . ' ч ' . (int) $time%60 . ' мин';

            $phpExcelObject->getActiveSheet()
                           ->setCellValueByColumnAndRow($col++, $str, $record['name']);
            $phpExcelObject->getActiveSheet()
                           ->setCellValueByColumnAndRow($col++, $str, $timeString);
            $phpExcelObject->getActiveSheet()
                           ->setCellValueByColumnAndRow($col++, $str, $record['total']);

        }

        $phpExcelObject->getActiveSheet()
            ->getStyle('A1:AQ1')
            ->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()->freezePane('AB2');
        $phpExcelObject->getActiveSheet()->setTitle('Statistic');

        $fileName = 'Statistic_' . $start->format('Y-m-d_H:i') . '—' . $end->format('Y-m-d_H:i');
        $writer = $this->container->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->container->get('phpexcel')->createStreamedResponse($writer);
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=' . $fileName . '.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
}
