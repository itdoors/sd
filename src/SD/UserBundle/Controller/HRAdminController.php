<?php

namespace SD\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HRAdminController extends Controller
{
    protected $baseTemplate = 'HRAdmin';

    /**
     * Executes filter clear action
     */
    public function indexAction()
    {
        return $this->render('SDUserBundle:' . $this->baseTemplate . ':index.html.twig');
    }
}
