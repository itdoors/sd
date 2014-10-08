<?php

namespace ITDoors\HistoryBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;

/**
 * HistoryController
 */
class HistoryController extends BaseFilterController
{

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /** @var History $filterNamespace */
    protected $filterNamespace = 'it_doors.history.namespace';
    protected $filterFormName = 'historyFilterForm';

    /**
     * Index action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction (Request $request)
    {
        $params = json_decode(stripslashes($request->request->get('params')), true);

        $filterNamespace = $this->container->getParameter($this->getNamespace())
            . $params['params']['modelName'] . $params['params']['modelId'];

        $session = $this->get('session');
        $session->set('paramsForHistory', json_encode($params));

        $return = array ();

        $return['params'] = json_encode($params);

        $filter = $this->filterFormName;

        $return['content'] = $this->renderView($params['html'], array (
            'filter' => $filter,
            'namespace' => $filterNamespace,
            'showFilter' => $params['params']['showFilter']
        ));


        return new Response(json_encode($return));
    }
    /**
     * List action
     *
     * @return Response
     */
    public function listAction ()
    {
        $session = $this->get('session');

        $params = json_decode(stripslashes($session->get('paramsForHistory', '')), true);

        $filterNamespace = $this->container->getParameter($this->getNamespace())
            . $params['params']['modelName'] . $params['params']['modelId'];

        $filters = $this->getFilters($filterNamespace);
        if (empty($filters)) {
            $filters['isFired'] = 'No fired';
            $this->setFilters($filterNamespace, $filters);
        }

        $service = $this->container->get($params['service']['alias']);
        $method = $params['service']['method'];

        $entityes = $service
            ->$method(
                $params['params']['modelName'],
                $params['params']['modelId'],
                $filterNamespace,
                $filters
            );

        $return = array ();

        $return['params'] = json_encode($params);

        if ($params['params']['showPagination'] === 'true') {
            if (key_exists('countPagination', $params['params']) && is_numeric($params['params']['countPagination'])) {
                $count = $params['params']['countPagination'];
            } else {
                $count = 10;
            }
            $page = $this->getPaginator($filterNamespace);
            if (!$page) {
                $page = 1;
            }
            $paginator = $this->container->get($this->paginator);
            $result = $paginator->paginate($entityes, $page, $count);
        } else {
            $result = $entityes;
        }

        return $this->render('ITDoorsHistoryBundle:History:list.html.twig', array (
                'entityes' => $result,
                'showPagination' => $params['params']['showPagination'],
                'namespace' => $filterNamespace
        ));
    }
}
