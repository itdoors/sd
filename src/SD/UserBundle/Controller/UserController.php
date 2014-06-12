<?php

namespace SD\UserBundle\Controller;

use SD\UserBundle\Entity\Staff;
use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use SD\UserBundle\Entity\User;
use SD\UserBundle\Entity\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;
use SD\UserBundle\Entity\Usercontactinfo;

/**
 * UserController
 */
class UserController extends BaseController
{

    protected $baseTemplate = 'User';
    protected $filterNamespace = 'staffFilterForm';
    protected $filterFormName = 'staffFilterForm';
    protected $baseRoute = 'sd_user_staff';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /** @var InvoiceService $service */
    protected $service = 'sd_user.service';

    /**
     * Executes index action
     *
     * @return string
     */
    public function staffAction()
    {
        $namespase = $this->filterNamespace;

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':staff.html.twig', array(
                'baseTemplate' => $this->baseTemplate,
                'namespase' => $namespase,
        ));
    }

    /**
     * stafflistAction
     * 
     * @return string
     */
    public function stafflistAction()
    {
        $namespase = $this->filterNamespace;
        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
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

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':stafflist.html.twig', array(
                'namespase' => $namespase,
                'items' => $pagination,
                'baseTemplate' => $this->baseTemplate
        ));
    }

    /**
     * Execute show action
     *
     * @param int $id
     *
     * @return string
     */
    public function showAction($id)
    {
        /** @var UserRepository $user */
        $user = $this->get('sd_user.repository');

        /** @var User $item */
        $item = $user->find($id);
        if (!$item) {
            return $this->redirect($this->generateUrl('sd_user_staff'));
        }
        /** @var Session $session */
        $session = $this->get('session');
        $session->set('userid', $id);


        $isCurrentUser = $id == $this->getUser()->getId();

        $isAdmin = $item->hasRole('ROLE_HRADMIN');

        /** @var UserService $service */
        $service = $this->container->get($this->service);

        $namespace = $this->filterNamespace . $id;

        $tab = $this->getTab($namespace);
        if (!$tab) {
            $tab = 'profile';
            $this->setTab($namespace, $tab);
        }

        if ($isCurrentUser || $isAdmin) {
            $options['settings'] = true;
        } else {
            $options['settings'] = false;
        }
        $tabs = $service->getTabs($options);

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'tabs' => $tabs,
                'tab' => $tab,
                'item' => $item,
                'namespace' => $namespace,
                'baseTemplate' => $this->baseTemplate,
                'isCurrentUser' => $isCurrentUser,
                'isAdmin' => $isAdmin
        ));
    }

    /**
     * Execute show action
     * 
     * @return string
     */
    public function showtabsAction()
    {
        /** @var Session $session */
        $session = $this->get('session');
        $userId = $session->get('userid', false);

        if (!$userId) {
            return $this->redirect($this->generateUrl('sd_user_staff'));
        }
        /** @var UserRepository $user */
        $user = $this->get('sd_user.repository');

        /** @var User $item */
        $item = $user->getStaffById((int) $userId);

        $namespace = $this->filterNamespace . $userId;

        $tab = $this->getTab($namespace);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Usercontactinfo $usercontactinfo */
        $usercontactinfo = $em->getRepository('SDUserBundle:Usercontactinfo')->findBy(array('user' => $userId));


        return $this->render('SDUserBundle:User:showTab' . $tab . '.html.twig', array(
                'item' => $item,
                'usercontactinfo' => $usercontactinfo,
        ));
    }

    /**
     * Execute show action
     * 
     * @return string
     */
    public function contactinfoaction()
    {
        /** @var Session $session */
        $session = $this->get('session');
        $userId = $session->get('userid', false);

        if (!$userId) {
            return $this->redirect($this->generateUrl('sd_user_staff'));
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Usercontactinfo $usercontactinfo */
        $usercontactinfo = $em->getRepository('SDUserBundle:Usercontactinfo')->findBy(array('user' => $userId));


        return $this->render('SDUserBundle:User:showContactinfo.html.twig', array(
                'usercontactinfo' => $usercontactinfo,
        ));
    }

    /**
     * Renders changePasswordForm
     *
     * @param int $id
     *
     * @return string
     */
    public function changePasswordFormAction($id)
    {
        $form = $this->createForm('changePasswordForm');

        $session = $this->get('session');

        $notice = $session->get('noticePassword');

        if ($notice) {
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
                'notice' => $notice
        ));
    }

    /**
     * Executes new action
     *
     * @param Request $request
     *
     * @throws \Exception
     * `
     * @return string
     */
    public function newAction(Request $request)
    {
        $sessionUser = $this->getUser();

        /* if (!$sessionUser->hasRole('ROLE_HRADMIN')) {
          return $this->redirect($this->generateUrl('sd_user_index'));
          } */

        $form = $this->createForm('userNewForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Connection $connection */
            $connection = $this->getDoctrine()->getConnection();

            $connection->beginTransaction();

            try {
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
            } catch (\Exception $e) {
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

    /**
     * Executes new action
     * 
     * @param Request $request
     * 
     * @return string
     */
    public function newstaffAction(Request $request)
    {
        $form = $this->createForm('userNewStaffForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Connection $connection */
            $connection = $this->getDoctrine()->getConnection();

            $connection->beginTransaction();

            try {
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
            } catch (\Exception $e) {
                $connection->rollBack();
                $em->close();
                throw $e;
            }

            return $this->redirect($this->generateUrl('sd_user_show', array('id' => $user->getId())));
        }

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':newstaff.html.twig', array(
                'form' => $form->createView(),
                'baseTemplate' => $this->baseTemplate
        ));
    }
}
