<?php

namespace Lists\OrganizationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
  public function indexAction()
  {
    $organizations = $this->getDoctrine()
      ->getRepository('ListsOrganizationBundle:Organization')
      ->findAll();

    if (!sizeof($organizations)) {
      throw $this->createNotFoundException(
        'No organizations found'
      );
    }

    return $this->render('ListsOrganizationBundle:Default:index.html.twig', array(
      'organizations' => $organizations
    ));
  }
}
