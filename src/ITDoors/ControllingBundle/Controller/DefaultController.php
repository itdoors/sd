<?php

namespace ITDoors\ControllingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * DefaultController
 *
 */
class DefaultController extends Controller
{

    /**
     * indexAction
     *
     * @return html Description
     */
    public function indexAction()
    {
        return $this->render('ITDoorsControllingBundle:Default:index.html.twig');
    }

}
