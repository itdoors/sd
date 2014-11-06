<?php

namespace ITDoors\OperBundle\Controller;

use ITDoors\OperBundle\Entity\CommentOrganizer;
use ITDoors\OperBundle\Entity\OperOrganizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * OperInfoController class
 *
 * Default controller for oper page
 */
class OperOrganizerController extends Controller
{

    protected $filterNamespace = 'ajax.filter.namespace.oper.department.table';

    /**
     * indexAction
     *
     * @return mixed[]
     */
    public function indexAction ()
    {
        $user = $this->getUser();
        $date = new \DateTime();

        $departments = $this->getDepartmentsForOrganizer($date, $user);

        $supervisor = $user->hasRole('ROLE_SUPERVISOR');

        $usersOper = array();
        if ($supervisor) {
            $usersOper = $this->opermanagerList();
        }

        return $this->render('ITDoorsOperBundle:Organizer:index.html.twig', array (
            'departments' => $departments,
            'supervisor' => $supervisor,
            'usersOper' => $usersOper
        ));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getRenderedDepartmentsAction(Request $request)
    {
        $idUser = $request->request->get('idUser');
        $dateSended = $request->request->get('date');

        $date = explode('(', $dateSended)[0];
        $date = new \DateTime($date);

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->find($idUser);


        $departments = $this->getDepartmentsForOrganizer($date, $user);

        $return['html'] = $this->renderView('ITDoorsOperBundle:Organizer:departmentBlock.html.twig', array (
            'departments' => $departments
        ));

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param \DateTime $date
     * @param User      $user
     *
     * @return mixed
     */
    private function getDepartmentsForOrganizer($date, $user)
    {
        /** @var AccessService $accessService */
        $accessService = $this->get('access.service');


        $allowedDepartmentsId = $accessService->getAllowedDepartmentsId($user);

        $repository = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments');

        $departments = $repository->getDepartmentsFromAccess($allowedDepartmentsId);

        $organizerRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        $organizersData = $organizerRepo->getDepartmentsInMonth($date, $user);

        foreach ($departments as $department) {
            $idDepartment = $department->getId();
            foreach ($organizersData as $organizerData) {
                if ($idDepartment == $organizerData['id']) {
                    $department->setSelected(true);
                    break;
                }
            }
        }

        return $departments;
    }

    /**
     * insertOrganizerDataAction
     *
     * @param Request $request
     *
     * @return mixed[]
     */
    public function insertOrganizerDataAction(Request $request)
    {
        $idDepartment = $request->request->get('id');
        $idUser = $request->request->get('idUser');

        $dateSended = $request->request->get('date');

        $date = explode('(', $dateSended)[0];
        //$date = new \DateTime($date);

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->find($idUser);

        $department = $this->getDoctrine()
            ->getRepository('ListsDepartmentBundle:Departments')->find($idDepartment);

        $startDatetime = new \DateTime($date);
        $startDatetime->setTime(9, 0, 0);

        $endDatetime = new \DateTime($date);
        $endDatetime->setTime(13, 0, 0);

        /*if (!$organizersData) {*/
            $organizerData = new OperOrganizer();
            $organizerData->setUser($user);
            $organizerData->setDepartment($department);
            $organizerData->setStartDatetime($startDatetime);
            $organizerData->setEndDatetime($endDatetime);
        /*}*/

        $em = $this->getDoctrine()->getManager();
        $em->persist($organizerData);
        $em->flush();


        $return['id'] = $organizerData->getId();
        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function getOrganizerEventsAction(Request $request)
    {
        $idUser = $request->request->get('idUser');


        $events = array();

        $user = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')->find($idUser);

        $organizerRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        $organizersData = $organizerRepo->findBy(array(
            'user' => $user
        ));

        foreach ($organizersData as $organizerData) {
            $color = '';
            if ($organizerData->getIsVisited()) {
                $color = 'green';
            }
            $events[] = array(
                'title' => $organizerData->getDepartment()->getName(),
                'start' => $organizerData->getStartDatetime()->format('Y-m-d H:i:s'),
                'end' => $organizerData->getEndDatetime()->format('Y-m-d H:i:s'),
                'allDay' => false,
                'id' => $organizerData->getId(),
                'color' => $color
            );
        }

        return new Response(json_encode($events));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editOrganizerAction(Request $request)
    {
        $idOrganizer = $request->request->get('id');

        $start = $request->request->get('start');
        $end = $request->request->get('end');

        $organizerRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        $organizer = $organizerRepo->find($idOrganizer);

        $organizer->setStartDatetime(new \DateTime($start));
        $organizer->setEndDatetime(new \DateTime($end));

        $em = $this->getDoctrine()->getManager();
        $em->persist($organizer);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteOrganizerAction(Request $request)
    {
        $idOrganizer = $request->request->get('id');


        $organizerRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        $organizer = $organizerRepo->find($idOrganizer);

        $comments = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:CommentOrganizer')
            ->findBy(array(
                'organizer' => $organizer
            ));

        $em = $this->getDoctrine()->getManager();

        if ($comments) {
            foreach ($comments as $comment) {
                $em->remove($comment);
            }
        }
        $em->remove($organizer);
        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function renderCommentsAction(Request $request)
    {
        $idOrganizer = $request->request->get('id');

        $organizerRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        $organizer = $organizerRepo->find($idOrganizer);

        $comments = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:CommentOrganizer')
            ->findBy(array(
                'organizer' => $organizer
            ));

        $return['html'] = $this->renderView('ITDoorsOperBundle:Organizer:comments.html.twig', array (
            'comments' => $comments
        ));

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addCommentAction(Request $request)
    {
        $idOrganizer = $request->request->get('id');
        $comment = $request->request->get('comment');
        $user = $this->getUser();

        $organizerRepo = $this->getDoctrine()
            ->getRepository('ITDoorsOperBundle:OperOrganizer');

        $organizer = $organizerRepo->find($idOrganizer);

        //if ($organizer->getUser() == $this->getUser()) {
        $organizer->setIsVisited(true);
        //}

        $em = $this->getDoctrine()->getManager();

        $organizerComment = new CommentOrganizer();
        $organizerComment->setUser($user);
        $organizerComment->setCreateDatetime(new \DateTime());
        $organizerComment->setOrganizer($organizer);
        $organizerComment->setValue($comment);

        $em->persist($organizerComment);

        $em->persist($organizer);

        $em->flush();

        $return['success'] = 1;

        return new Response(json_encode($return));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    private function opermanagerList()
    {
        $return = array();

        $usersOper = $this->getDoctrine()
            ->getRepository('SDUserBundle:User')
            ->findByRole('ROLE_OPER');

        array_walk($usersOper, function ($userOper, $key) use (&$return) {
            if ($userOper->getStuff()) {
                $return[] = array('id' =>  $userOper->getId(), 'fullName' => $userOper->getFullName());
            }
        });


        return $return;
    }
}
