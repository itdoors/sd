<?php

namespace Lists\DogovorBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\HandlingBundle\Entity\HandlingDogovorRepository;

/**
 * Class ProjectController
 */
class ProjectController extends BaseFilterController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('ListsDogovorBundle:Project:index.html.twig');
    }

    /**
     * @param int $dogovorId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($dogovorId)
    {
        /** @var DogovorRepository $dogovorRepository */
        $dogovor = $this->getDoctrine()
            ->getManager()
            ->getRepository('ListsDogovorBundle:Dogovor')->find($dogovorId);

        $projects = $dogovor->getProjects();
            
        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser(), $dogovor);

        return $this->render('ListsDogovorBundle:Project:list.html.twig', array(
            'dogovorId' => $dogovorId,
            'projects' => $projects,
            'access' => $access
        ));
    }
}
