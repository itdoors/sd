<?php

namespace Lists\CompanystructureBundle\Controller;

use ITDoors\CommonBundle\Controller\BaseFilterController as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class StructureController
 */
class StructureController extends BaseController
{
    protected $baseTemplate = 'Structure';

    /** @var Article $filterNamespace */
    protected $filterNamespace = 'filter.namespace.comapnystructure';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function indexAction()
    {
        return $this->render('ListsCompanystructureBundle:' . $this->baseTemplate . ':index.html.twig');
    }

    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function listAction(Request $request)
    {
        var_dump($request->request);die;
        $parent = $request->request->get('parent');

        $data = array();

        $states = array(
                "success",
                "info",
                "danger",
                "warning"
        );
        var_dump($parent);die;

        if ($parent == "#") {
            $objects = $this->getDoctrine()
                ->getRepository('ListsCompanystructureBundle:Companystructure')->getRootNodes();
                foreach ($objects as $object) {
                    $data[] = array(
                            "id" => "node_".$object->getId(),  
                            "text" => $object->getName(), 
                            "icon" => "fa fa-folder icon-lg icon-state-" . ($states[rand(0, 3)]),
                            "children" => true, 
                            "type" => "root"
                    );
                }
        } else {
            if (rand(1, 5) === 3) {
                    $data[] = array(
                            "id" => "node_" . time() . rand(1, 100000), 
                            "icon" => "fa fa-file fa-large icon-state-default",
                            "text" => "No childs ", 
                            "state" => array("disabled" => true),
                            "children" => false
                    );
            } else {
		for($i = 1; $i < rand(2, 4); $i++) {
			$data[] = array(
				"id" => "node_" . time() . rand(1, 100000), 
				"icon" => ( rand(0, 3) == 2 ? "fa fa-file icon-lg" : "fa fa-folder icon-lg")." icon-state-" . ($states[rand(0, 3)]),
				"text" => "Node " . time(), 
				"children" => ( rand(0, 3) == 2 ? false : true)
			);
		}
            }
        }
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
