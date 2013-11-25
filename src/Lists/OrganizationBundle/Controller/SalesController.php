<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SalesController extends Controller
{
  public function indexAction()
  {
    return $this->render('ListsOrganizationBundle:Sales:index.html.twig');
  }

  public function ajaxIndexAction()
  {
    $page = $this->get('request')->query->get('sEcho', 1);
    $iDisplayLength = $this->get('request')->query->get('iDisplayLength', 20);

    /** @var \Lists\OrganizationBundle\Entity\OrganizationRepository $organizationsRepository */
    $organizationsRepository = $this->getDoctrine()
      ->getRepository('ListsOrganizationBundle:Organization');

    /** @var \Doctrine\ORM\Query */
    $organizationsQuery = $organizationsRepository->getAllForSalesQuery();

    /** @var \Knp\Component\Pager\Paginator $paginator */
    $paginator  = $this->get('knp_paginator');

    /** @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination $pagination*/
    $pagination = $paginator->paginate(
      $organizationsQuery,
      $page,
      $iDisplayLength
    );

    $iTotalRecords = $pagination->getTotalItemCount();
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = 0;
    $sEcho = $page;

    $records = array();
    $records["aaData"] = $this->ProcessArrayForAjaxJsonUotput($pagination->getItems());

    $records["sEcho"] = $sEcho;
    $records["iTotalRecords"] = $iTotalRecords;
    $records["iTotalDisplayRecords"] = $iTotalRecords;

    return new Response(json_encode($records));
  }

  /**
   * Process array for ajax json output
   *
   * @param mixed[] $in
   *
   * @return mixed $out
   */
  public function ProcessArrayForAjaxJsonUotput($in)
  {
    $out = array();

    foreach ($in as $key => $value)
    {
      $out[] = array_values($value);
    }

    return $out;
  }
}
