<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesController extends Controller
{
  public function indexAction()
  {
    // Get organization filter

    $organizationId = $this->get('session')->get('handling.filter.organization_id');

    if (!$organizationId)
    {
      return $this->redirect($this->generateUrl('lists_sales_organization_index'));
    }

    $page = $this->get('request')->query->get('page', 1);

    /** @var \Lists\HandlingBundle\Entity\HandlingRepository $handlingRepository */
    $handlingRepository = $this->getDoctrine()
      ->getRepository('ListsHandlingBundle:Handling');

    /** @var \Doctrine\ORM\Query $handlingQuery */
    $handlingQuery = $handlingRepository->getAllForSalesQuery($organizationId);

    /** @var \Knp\Component\Pager\Paginator $paginator */
    $paginator  = $this->get('knp_paginator');

    $pagination = $paginator->paginate(
      $handlingQuery,
      $page,
      20
    );

    return $this->render('ListsHandlingBundle:Sales:index.html.twig', array(
      'pagination' => $pagination
    ));
  }


  /**
   * Execute addOrganizationFilter action
   */
  public function addOrganizationFilterAction($organization_id)
  {
    $this->get('session')->set('handling.filter.organization_id', $organization_id);

    return $this->redirect($this->generateUrl('lists_sales_handling_index'));
  }
}
