<?php

namespace Lists\DogovorBundle\Controller;

use Doctrine\ORM\Query;
use Lists\DogovorBundle\Entity\DopDogovorRepository;
use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;

/**
 * Class DopDogovorController
 */
class DopDogovorController extends BaseController
{
    protected $filterNamespace = 'base.dopdogovor.filters';
    protected $baseRoutePrefix = 'dopdogovor';
    protected $baseTemplate = 'DopDogovor';

    /**
     * Returns list of dop dogovors by dogovorId
     *
     * @param int $dogovorId
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction($dogovorId)
    {
        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser());
        
        /** @var \Lists\DogovorBundle\Entity\DopDogovorRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DopDogovor');

        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->getAllByDogovorIdQuery($dogovorId);

        $items = $query->getResult();

        return $this->render('ListsDogovorBundle:DopDogovor:list.html.twig', array(
            'items' => $items,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'access' => $access
        ));
    }

    /**
     * Renders single element of dogovor list
     *
     * @param int $id
     *
     * @return string
     */
    public function elementAction($id)
    {
        /** @var DopDogovorRepository $ddr */
        $ddr = $this->get('lists_dogovor.dopdogovor.repository');

        /** @var Query $query */
        $query = $ddr->getAllByDogovorIdQuery(null, $id);

        $item = $query->getSingleResult();

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate. ':element.html.twig', array(
            'item' => $item,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
