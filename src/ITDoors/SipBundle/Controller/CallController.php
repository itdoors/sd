<?php

namespace ITDoors\SipBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SipController
 */
class CallController extends BaseFilterController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('ITDoorsSipBundle:Call:index.html.twig');
    }
}
