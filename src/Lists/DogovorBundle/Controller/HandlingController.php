<?php

namespace Lists\DogovorBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\HandlingBundle\Entity\HandlingCompetitorRepository;
use Lists\HandlingBundle\Entity\HandlingDogovorRepository;

/**
 * Class HandlingController
 */
class HandlingController extends BaseFilterController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('ListsDogovorBundle:Handling:index.html.twig');
    }

    /**
     * @param int $dogovorId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($dogovorId)
    {
        /** @var HandlingDogovorRepository $hdr */
        $hdr = $this->get('handling.dogovor.repository');

        $results = $hdr->getList($dogovorId);

        return $this->render('ListsDogovorBundle:Handling:list.html.twig', array(
            'dogovorId' => $dogovorId,
            'results' => $results
        ));
    }

    /**
     * @param int $dogovorId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formAction($dogovorId)
    {
        return $this->render('ListsDogovorBundle:Handling:form.html.twig', array(
            'dogovorId' => $dogovorId
        ));
    }
}
