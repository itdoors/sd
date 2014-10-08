<?php

namespace Lists\DogovorBundle\Controller;

use Lists\DogovorBundle\Entity\DogovorHistoryRepository;
use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;

/**
 * Class DogovorHistoryController
 */
class DogovorHistoryController extends BaseController
{
    protected $filterNamespace = 'base.dogovor.history.filters';
    protected $baseRoutePrefix = 'dogovor_history';
    protected $baseTemplate = 'DogovorHistory';

    /**
     * Renders prolongation history list
     *
     * @param int $dogovorId
     *
     * @return string
     */
    public function listAction($dogovorId)
    {
        /** @var DogovorHistoryRepository $dh */
        $dh = $this->get('lists_dogovor.history.repository');

        $items = $dh
            ->getListByDogovorId($dogovorId)
            ->getResult();

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':list.html.twig', array(
            'items' => $items,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix
        ));
    }
}
