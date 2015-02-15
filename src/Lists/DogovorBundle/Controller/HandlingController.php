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

         /** @var DogovorRepository $dogovorRepository */
        $dogovorRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('ListsDogovorBundle:Dogovor');

        $dogovor = $dogovorRepository->find($dogovorId);

        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser(), $dogovor);

        return $this->render('ListsDogovorBundle:Handling:list.html.twig', array(
            'dogovorId' => $dogovorId,
            'results' => $results,
            'access' => $access
        ));
    }

    /**
     * @param int $dogovorId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formAction($dogovorId)
    {
        /** @var DogovorRepository $dogovorRepository */
        $dogovorRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('ListsDogovorBundle:Dogovor');

        $dogovor = $dogovorRepository->find($dogovorId);

        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser(), $dogovor);

        if (!$access->canEdit()) {
            return $this->render('ListsDogovorBundle:Dogovor:noAccess.html.twig');
        }

        return $this->render('ListsDogovorBundle:Handling:form.html.twig', array(
            'dogovorId' => $dogovorId
        ));
    }
}
