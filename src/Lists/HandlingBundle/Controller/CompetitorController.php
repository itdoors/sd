<?php

namespace Lists\HandlingBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Lists\HandlingBundle\Entity\HandlingCompetitorRepository;

/**
 * Class CompetitorContaroller
 */
class CompetitorController extends BaseFilterController
{

    /**
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($handlingId)
    {
        return $this->render('ListsHandlingBundle:Competitor:index.html.twig', array(
            'handlingId' => $handlingId
        ));
    }

    /**
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($handlingId)
    {
        /** @var HandlingCompetitorRepository $hcr */
        $hcr = $this->get('handling.competitor.repository');

        $results = $hcr->getList($handlingId);

        return $this->render('ListsHandlingBundle:Competitor:list.html.twig', array(
            'handlingId' => $handlingId,
            'results' => $results
        ));
    }

    /**
     * @param int $handlingId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formAction($handlingId)
    {
        return $this->render('ListsHandlingBundle:Competitor:form.html.twig', array(
            'handlingId' => $handlingId
        ));
    }
}
