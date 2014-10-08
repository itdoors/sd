<?php

namespace Lists\CityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @param string $name
     *
     * @return Response
     */
    public function indexAction($name)
    {
        return $this->render('ListsCityBundle:Default:index.html.twig', array('name' => $name));
    }
}
