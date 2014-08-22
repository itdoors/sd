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
    /**
     * Index action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $params = json_decode(stripslashes($request->request->get('params')), true);

        $session = $this->get('session');
        $session->set('paramsForHistory', json_encode($params));

        $return = array();

        $return['params'] = json_encode($params);

        $return['content'] = $this->renderView($params['html']);


        return new Response(json_encode($return));
    }
    /**
     * List action
     *
     * @return Response
     */
    public function listAction()
    {
        $session = $this->get('session');

        $params = json_decode(stripslashes($session->get('paramsForHistory', '')), true);

        $filterNamespace = $this->container->getParameter($this->getNamespace()).$params['params']['modelName'].$params['params']['modelId'];

        $service = $this->container->get($params['service']['alias']);
        $method = $params['service']['method'];

        $entityes = $service->$method($params['params']['modelName'], $params['params']['modelId'], $filterNamespace);

        $return = array();

        $return['params'] = json_encode($params);

        if ($params['params']['pagination'] === 'true') {
            $page = $this->getPaginator($filterNamespace);
            if (!$page) {
                $page = 1;
            }
            $paginator = $this->container->get($this->paginator);
            $result = $paginator->paginate($entityes, $page, 10);
        } else {
            $result = $entityes->getResult();
        }

        return $this->render('ITDoorsHistoryBundle:History:list.html.twig', array(
            'entityes' => $result,
            'showPagination' => $params['params']['pagination'],
            'namespace' => $filterNamespace
        ));
    }
}