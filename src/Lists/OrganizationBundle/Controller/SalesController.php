<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SalesController extends Controller
{
  public function indexAction()
  {
    $page = $this->get('request')->query->get('page', 1);

    /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
    $organizationsRepository = $this->getDoctrine()
      ->getRepository('ListsOrganizationBundle:Organization');

    /** @var \Doctrine\ORM\Query */
    $organizationsQuery = $organizationsRepository->getAllForSalesQuery();

    /** @var \Knp\Component\Pager\Paginator $paginator */
    $paginator  = $this->get('knp_paginator');

    $pagination = $paginator->paginate(
      $organizationsQuery,
      $page,
      20
    );

    return $this->render('ListsOrganizationBundle:Sales:index.html.twig', array(
      'pagination' => $pagination,
    ));
  }
}
