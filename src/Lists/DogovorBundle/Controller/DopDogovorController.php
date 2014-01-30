<?php

namespace Lists\DogovorBundle\Controller;

use SD\CommonBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class DopDogovorController extends BaseController
{
    protected $filterNamespace = 'base.dopdogovor.filters';
    protected $baseRoutePrefix = 'dopdogovor';
    protected $baseTemplate = 'DopDogovor';

    public function listAction($dogovorId)
    {
        /** @var \Lists\DogovorBundle\Entity\DopDogovorRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DopDogovor');

        /** @var \Doctrine\ORM\Query $query */
        $query = $repository->getAllByDogovorIdQuery($dogovorId);

        $items = $query->getResult();

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate. ':list.html.twig', array(
            'items' => $items,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
