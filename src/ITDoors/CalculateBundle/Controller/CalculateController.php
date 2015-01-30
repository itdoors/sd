<?php

namespace ITDoors\CalculateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CalculateController extends Controller
{
    public function pageAction()
    {
        $calculateService = $this->get('it_doors_calculate.service');
        $data = $calculateService->getData();

        return $this->render('ITDoorsCalculateBundle:Calculate:index.html.twig', array('data' => $data));
    }
}
