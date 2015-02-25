<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lists\OrganizationBundle\Entity\OrganizationUser;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Lists\OrganizationBundle\Form\OrganizationCreateForm;

/**
 * Class PayerController
 */
class PayerController extends Controller
{
    protected $filterNamespace = 'payer.filters';
    protected $filterFormName = 'organizationSalesFilterForm';
    /**
     * indexAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction ()
    {
        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser());

        return $this->render('ListsOrganizationBundle:Payer:index.html.twig', array (
                'access' => $access
        ));
    }
    /**
     * listAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction ()
    {
        $isShowUrl = true;

        /** @var \SD\UserBundle\Entity\User $user */
        $user = $this->getUser();

        /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
        $organizationsRepository = $this->getDoctrine()
            ->getRepository('ListsOrganizationBundle:Organization');
 
        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($user);
        if (!$access->canSeePayer()) {
            throw new \Exception('No access', 403);
        }
        $organiations = $organizationsRepository->getPayerQuery()->getQuery()->getResult();


        return $this->render('ListsOrganizationBundle:Payer:list.html.twig', array (
                'organiations' => $organiations,
                'isShowUrl' => $isShowUrl,
        ));
    }
    /**
     * Executes new action
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newAction (Request $request)
    {
        $user = $this->getUser();

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($user);

        if (!$access->canAddPayer()) {
            throw new \Exception('No access');
        }

        $form = $this->createForm('organizationSalesForm');

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
            $organization = $form->getData();
 
            $user = $this->getUser();

            $organization->setCreator($user);
            $organization->setIsPayer(true);
            $organization->setIsSelf(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($organization);
            $em->flush();

            $lookup = $this->getDoctrine()->getRepository('ListsLookupBundle:lookup')
                ->findOneBy(array ('lukey' => 'manager_organization'));
            $manager = new OrganizationUser();
            $manager->setOrganization($organization);
            $manager->setUser($user);
            $manager->setRole($lookup);
            $em->persist($manager);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_organization_payer_show', array (
                'id' => $organization->getId()
            )));
        }

        return $this->render('ListsOrganizationBundle:Payer:new.html.twig', array (
                'filterForm' => $form->createView(),
                'filterFormName' => $this->filterFormName
        ));
    }
    /**
     * Executes show action
     *
     * @param integer $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction ($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \Lists\OrganizationBundle\Entity\Organization $organization */
        $organization = $em
            ->getRepository('ListsOrganizationBundle:Organization')
            ->find($id);
        if (!$organization) {
            return $this->render('ListsOrganizationBundle:Organization:notFound.html.twig');
        }

        $service = $this->get('lists_organization.service');
        $access = $service->checkAccess($this->getUser(), $organization);

        if (!$access->canSee()) {
            return $this->render('ListsOrganizationBundle:Organization:noAccess.html.twig', array(
                'organization' => $organization
            ));
        }

        if ($organization->getParent()) {
            return $this->redirect($this->generateUrl('lists_organization_payer_show', array (
                'id' => $organization->getParentId()
            )));
        }
        $lookups = $em->getRepository('ListsLookupBundle:Lookup')->getGroupOrganizationQuery()->getQuery()->getResult();

        $managerForm = $this->createForm('organizationUserForm');

        return $this->render('ListsOrganizationBundle:Payer:show.html.twig', array (
                'organization' => $organization,
                'lookups' => $lookups,
                'filterFormName' => $this->filterFormName,
                'managerForm' => $managerForm->createView(),
                'access' => $access
        ));
    }
}
