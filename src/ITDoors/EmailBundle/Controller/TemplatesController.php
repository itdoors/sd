<?php

namespace ITDoors\EmailBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController;
use Doctrine\ORM\EntityManager;

/**
 * Class TemplatesController
 * 
 * @package ITDoors\EmailBundle\Controller
 */
class TemplatesController extends BaseFilterController
{
    /**
     * indexAction
     * 
     * @return render
     */
    public function indexAction()
    {
        return $this->render('ITDoorsEmailBundle:Templates:index.html.twig');
    }

    /**
     * listAction
     * 
     * @return type
     */
    public function listAction()
    {
        /** @val EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var EmailRepository $templates */
        $templates = $em->getRepository('ITDoorsEmailBundle:Email')->findAll();

        return $this->render('ITDoorsEmailBundle:Templates:list.html.twig', array(
            'templates' => $templates
            ));
    }
}
