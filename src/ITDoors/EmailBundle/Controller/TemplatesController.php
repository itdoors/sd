<?php

namespace ITDoors\EmailBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController;
use Doctrine\ORM\EntityManager;

class TemplatesController extends BaseFilterController
{
    public function indexAction()
    {
        $name = 'sdf';
        return $this->render('ITDoorsEmailBundle:Templates:index.html.twig', array('name' => $name));
    }
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
