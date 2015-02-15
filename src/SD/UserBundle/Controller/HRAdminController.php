<?php

namespace SD\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * HRAdminController
 */
class HRAdminController extends Controller
{
    protected $baseTemplate = 'HRAdmin';

    /**
     * Executes filter clear action
     *
     * @return string
     */
    public function indexAction()
    {
        return $this->render('SDUserBundle:' . $this->baseTemplate . ':index.html.twig');
    }
}
