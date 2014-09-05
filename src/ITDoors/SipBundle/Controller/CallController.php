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
        $phone = $this->getRequest()->get('phone');
        $namespace = 'call'.  $this->getRequest()->get('model');
        $params = $this->getSessionValues($namespace, 'call');
        $form = null;
        if (key_exists('formName', $params)) {
            $form = $this->createForm($params['formName'])->createView();

            return $this->render('ITDoorsSipBundle:Call:index.html.twig', array(
                'phone' => $phone,
                'params' => $params,
                'form' => $form
            ));
        }

        return $this->render('ITDoorsSipBundle:Call:index.html.twig', array(
            'phone' => $phone,
            'params' => $params
        ));
    }
}
