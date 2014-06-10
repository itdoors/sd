<?php

namespace SD\UserBundle\Controller;

use SD\UserBundle\Entity\Staff;
use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use SD\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class UserController extends BaseController
{
    protected $baseTemplate = 'User';
    protected $filterNamespace = 'staffFilterForm';
    protected $filterFormName = 'staffFilterForm';
    protected $baseRoute = 'sd_user_staff';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';
    /**
     * Executes index action
     */
//    public function indexAction()
//    {
//        $users = $this->get('sd_user.repository')->getOnlyStaff()
//            ->getQuery()
//            ->getResult();
//
//        return $this->render('SDUserBundle:' . $this->baseTemplate . ':index.html.twig', array(
//                'items' => $users,
//                'baseTemplate' => $this->baseTemplate
//            ));
//    }
    /**
     * Executes index action
     */
    public function staffAction()
    {

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':staff.html.twig', array(
                'baseTemplate' => $this->baseTemplate
            ));
    }

    public function stafflistAction()
    {
        $filterNamespace = $this->filterNamespace;
        
        $Form = $this->createForm($this->filterFormName);

        $filterForm = $Form->bind($this->getFilters());
        
        $users = $this->get('sd_user.repository')->getAllForUserQuery($this->getFilters());
        $entities = $users['entity'];
        $count = $users['count'];
        
         $namespasePagin = $filterNamespace.'P';
//        $page = $this->getPaginator($namespasePagin);
//        if (!$page) {
//            $page = 1;
//        }
//
//        $paginator = $this->container->get($this->paginator);
//        $entities->setHint($this->paginator . '.count', $count);
//        $pagination = $paginator->paginate($entities, $page, 10);

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':stafflist.html.twig', array(
                'namespasePagin' => $namespasePagin,
                'items' => $entities->getResult(),
                'filterForm' => $filterForm->createView(),
                'filterFormName' => $this->filterFormName,
                'baseTemplate' => $this->baseTemplate
            ));
    }
    /**
     * Execute show action
     */
    public function showAction($id)
    {
        /** @var User $user */
        $user = $this->get('sd_user.repository')->find($id);

        $isCurrentUser = $id == $this->getUser()->getId();

        $isAdmin = $user->hasRole('ROLE_HRADMIN');

        if (!$user)
        {
            return $this->render($this->generateUrl('sd_user_index'));
        }

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'item' => $user,
                'baseTemplate' => $this->baseTemplate,
                'isCurrentUser' => $isCurrentUser,
                'isAdmin' => $isAdmin
            ));
    }

    /**
     * Renders changePasswordForm
     */
    public function changePasswordFormAction($id)
    {
        $form = $this->createForm('changePasswordForm');

        $session = $this->get('session');

        $notice = $session->get('noticePassword');

        if ($notice)
        {
            $session->remove('noticePassword');
        }

        return $this->render('SDCommonBundle:AjaxForm:changePasswordForm.html.twig', array(
            'form' => $form->createView(),
            'formName' => 'changePasswordForm',
            'postFunction' => 'updateList',
            'postTargetId' => 'change-password-form',
            'targetId' => 'change-password-form',
            'defaultData' => array(),
            'model' => 'User',
            'modelId' => $id,
            'notice' =>$notice
        ));
    }

    /**
     * Executes new action
     */
    public function newAction(Request $request)
    {
        $sessionUser = $this->getUser();

        /*if (!$sessionUser->hasRole('ROLE_HRADMIN'))
        {
            return $this->redirect($this->generateUrl('sd_user_index'));
        }*/

        $form = $this->createForm('userNewForm');

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            /** @var Connection $connection */
            $connection = $this->getDoctrine()->getConnection();

            $connection->beginTransaction();

            try
            {
                $user = $form->getData();

                $formData = $request->request->get($form->getName());

                $em->persist($user);
                $em->flush();

                $staff = new Staff();

                $staff->setUser($user);
                $staff->setMobilephone($formData['mobilephone']);
                $staff->setStuffclass('stuff');

                $em->persist($staff);
                $em->flush();

                $connection->commit();
            }
            catch (\Exception $e)
            {
                $connection->rollBack();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('sd_user_show', array('id' => $user->getId())));
        }

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':new.html.twig', array(
                'form' => $form->createView(),
                'baseTemplate' => $this->baseTemplate
            ));
    }
}
