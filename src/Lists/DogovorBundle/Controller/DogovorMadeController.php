<?php

namespace Lists\DogovorBundle\Controller;

use Lists\DogovorBundle\Entity\DogovorRepository;
use ITDoors\AjaxBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;

/**
 * Class DogovorMadeController
 */
class DogovorMadeController extends BaseController
{
    protected $filterNamespace = 'base.dogovor.filters';
    protected $filterFormName = 'dogovorMadeFilterForm';
    protected $baseTemplate = 'DogovorMade';

    /**
     * indexAction
     *
     * @return Response
     */
    public function indexAction()
    {
        $namespase = $this->getNamespace().'Made';
        $filter = $this->filterFormName;

        return $this->render('ListsDogovorBundle:DogovorMade:index.html.twig', array(
            'namespase' => $namespase,
            'filter' => $filter
        ));
    }

    /**
     * indexAction
     *
     * @return Response
     */
    public function listAction()
    {
        $namespase = $this->getNamespace().'Made';
        $namespaseDogovor = $this->getNamespace().'1';
        $namespaseDop = $this->getNamespace().'2';

        /** @var \Knp\Component\Pager\Paginator $paginator */
        $paginator  = $this->get('knp_paginator');

        $filters = $this->getFilters($namespase);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($namespase, $filters);
        }

        $pageDogovor = $this->getPaginator($namespaseDogovor);
        if (!$pageDogovor) {
            $pageDogovor = 1;
        }
         /** @var DogovorRepository $dogovor */
        $dogovor = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor');
        /** @var Query $dogovorQuery*/
        $dogovorQuery = $dogovor->getAllForDogovorQuery($filters);
        $dogovors = $paginator->paginate(
            $dogovorQuery,
            $pageDogovor,
            10
        );


        $pageDop = $this->getPaginator($namespaseDop);
        if (!$pageDop) {
            $pageDop = 1;
        }
         /** @var DopDogovorRepository $dop */
        $dop = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:DopDogovor');
        /** @var Query $dogovorQuery*/
        $dopQuery = $dop->getAllForDopDogovorQuery($filters);
        $dopDogovors = $paginator->paginate(
            $dopQuery,
            $pageDop,
            10
        );

        $service = $this->get('lists_dogovor.service');
        $access = $service->checkAccess($this->getUser());

        return $this->render('ListsDogovorBundle:DogovorMade:list.html.twig', array(
            'dogovors' => $dogovors,
            'namespaseDogovor' => $namespaseDogovor,
            'dopDogovors' => $dopDogovors,
            'namespaseDop' => $namespaseDop,
            'access' => $access
        ));
    }
}
