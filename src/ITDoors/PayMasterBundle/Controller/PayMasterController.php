<?php

namespace ITDoors\PayMasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PayMasterController
 */
class PayMasterController extends Controller
{
    /**
     * indexAction
     * 
     * @return Response
     */
    public function indexAction()
    {
        $service = $this->get('it_doors_pay_master.service');
        $access = $service->checkAccess($this->getUser());
        $nameSpacePayMaster = 'it_doors_pay_master_nameSpace';

        return $this->render('ITDoorsPayMasterBundle:PayMaster:index.html.twig', array(
            'access' => $access,
            'nameSpacePayMaster' => $nameSpacePayMaster
        ));
    }
    /**
     * tabAction
     * 
     * @param string $tab new|urgent|payment|sponsored|rejected
     * 
     * @return Response
     */
    public function tabAction($tab)
    {
        $service = $this->get('it_doors_pay_master.service');
        $access = $service->checkAccess($this->getUser());
        $nameSpacePayMaster = 'it_doors_pay_master_nameSpace'.'_'.$tab;
        $em = $this->getDoctrine()->getManager();
        $baseFilter = $this->get('it_doors_ajax.base_filter_service');
        $page = $baseFilter->getPaginator($nameSpacePayMaster);
        if (!$page) {
            $page = 1;
        }
        $orders = $baseFilter->getOrdering($nameSpacePayMaster);
            
        $payMasterRepository = $em->getRepository('ITDoorsPayMasterBundle:PayMaster');
        /** @var \Doctrine\ORM\Query */
        $payMasterQuery = $payMasterRepository->forTab($tab, $orders);
        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        $entities = $paginator->paginate(
            $payMasterQuery,
            $page,
            20
        );

        return $this->render('ITDoorsPayMasterBundle:PayMaster:tab.html.twig', array(
            'access' => $access,
            'nameSpacePayMaster' => $nameSpacePayMaster,
            'tab' => $tab,
            'entities' => $entities
        ));
    }
    /**
     * newAction
     * 
     * @param Request $request
     * 
     * @return Response
     * 
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $service = $this->get('it_doors_pay_master.service');
        $access = $service->checkAccess($this->getUser());
        if (!$access->canAdd()) {
            throw new \Exception('No access', 403);
        }
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('payMasterNewForm');

        $formData = $request->request->get($form->getName());
        if (strpos($formData['currentAccount'], 'isNew_') !== false) {
            $name = explode('isNew_', $formData['currentAccount']);
            $organization = $em->getRepository('ListsOrganizationBundle:Organization')->find($formData['contractor']);
            $type = $em->getRepository('ListsOrganizationBundle:OrganizationCurrentAccountType')->find(2);
            $bank = $em->getRepository('ListsOrganizationBundle:Bank')->find($formData['mfo']);
            $currentAccountFind = $em->getRepository('ListsOrganizationBundle:OrganizationCurrentAccount')->findOneBy(array(
                'bank' => $bank,
                'name' => $name[1],
                'organization' => $organization,
                'typeAccount' => $type
            ));
            if (!$currentAccountFind) {
                $currentAccount = new \Lists\OrganizationBundle\Entity\OrganizationCurrentAccount();
                $currentAccount->setBank($bank);
                $currentAccount->setName($name[1]);
                $currentAccount->setOrganization($organization);
                $currentAccount->setTypeAccount($type);
                $em->persist($currentAccount);
                $em->flush();
                $em->refresh($currentAccount);
            } else {
                $currentAccount = $currentAccountFind;
            }
            $formData['currentAccount'] = $currentAccount->getId();
            $request->request->set($form->getName(), $formData);
        }
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->getConnection()->beginTransaction();
            try {
                $user = $this->getUser();
                $payMaster = $form->getData();
                $payMaster->setCreator($user);

                $em->persist($payMaster);
                $em->flush();

                $file = $form['scan']->getData();

                if ($file) {
                    $payMaster->setFileTemp($file);
                    $payMaster->upload();
                    $em->persist($payMaster);
                    $em->flush();
                }

                $em->getConnection()->commit();
            } catch (\Exception $e) {
                $em->getConnection()->rollback();
                throw $e;
            }

            return $this->redirect($this->generateUrl('it_doors_pay_master_index'));
        }

        return $this->render('ITDoorsPayMasterBundle:PayMaster:new.html.twig', array(
            'access' => $access,
            'form' => $form->createView()
        ));
    }
}
