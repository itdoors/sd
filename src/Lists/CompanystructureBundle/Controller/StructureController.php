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
    public function listAction()
    {
         return $this->render('ListsCompanystructureBundle:' . $this->baseTemplate . ':list.html.twig');
    }
    
    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function getListAction()
    {
        $parent = $this->get('request')->query->get('parent');
        $data = array();
        $router = $this->container->get('router');

        $repository = $this->getDoctrine()
                ->getRepository('ListsCompanystructureBundle:Companystructure');

        if ($parent == "#") {
            $objects = $repository->getRootNodes();
                foreach ($objects as $object) {
                    $name = $object->getName();
                    if ($object->getStuff()) {
                        $name .= $this->getText($object->getId(), $object->getStuff()->getUser());
                    } else {
                        $name .= $this->getText($object->getId(), null);
                    }
                    $data[] = array(
                            "id" => $object->getId(),  
                            "text" => $name, 
                            "icon" => "fa fa-folder icon-lg icon-state-success",
                            "children" =>  true, 
                            "type" => "root"
                    );
                }
        } else {
            $object = $repository->find((int)$parent);
            $childrens = $repository->getChildren($object, true);
            if ($childrens) {
                foreach ($childrens as $children) {
                    $name = $children->getName();
                    if ($children->getStuff()) {
                        $name .= $this->getText($children->getId(), $children->getStuff()->getUser());
                    } else {
                        $name .= $this->getText($children->getId(), null);
                    }
                    $data[] = array(
                            "id" => $children->getId(),  
                            "text" => $name, 
                            "icon" => "fa fa-folder icon-lg icon-state-success",
                            "children" =>  true,
                    );
                }
                $stuffRepository = $this->getDoctrine()
                        ->getRepository('SDUserBundle:Stuff');
                    $employees = $stuffRepository->getStuffForCompanystructure((int)$parent);
                    foreach ($employees as $employ) {
                        $url = $router->generate(
                            'sd_user_show',
                            array('id' => $employ->getUser()->getId())
                        );
                        $data[] = array(
                                "id" => 'stuff_'.$employ->getId(),  
                                "text" => '<a data-href="true" href="'.$url.'">'.$employ->getUser()->getLastName().' '.$employ->getUser()->getFirstName().'</a> ('.$employ->getUser()->getPosition().')', 
                                "icon" => "fa fa-folder icon-lg icon-state-info",
                                "children" =>  false,
                        );
                    }
            
            } else {
                $stuffRepository = $this->getDoctrine()
                    ->getRepository('SDUserBundle:Stuff');
                $employees = $stuffRepository->getStuffForCompanystructure((int)$parent);
                foreach ($employees as $employ) {
                    $url = $router->generate(
                            'sd_user_show',
                            array('id' => $employ->getUser()->getId())
                        );
                    $data[] = array(
                            "id" => 'stuff_'.$employ->getId(),  
                            "text" => '<a data-href="true" href="'.$url.'">'.$employ->getUser()->getLastName().' '.$employ->getUser()->getFirstName().'</a> ('.$employ->getUser()->getPosition().')', 
                            "icon" => "fa fa-folder icon-lg icon-state-info",
                            "children" =>  false,
                    );
                }
            }
        }
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    
    /**
     * @param integer $companystructureId
     * @param User    $user
     * 
     * @return string
     */
    private function getText($companystructureId, $user)
    {
         /** @var Translator $translator */
        $translator = $this->get('translator');
            
        $name = '';
        if ($user) {
            $url = $this->container->get('router')->generate(
                            'sd_user_show',
                            array('id' => $user->getId())
                        );
            $name .= ' | <a data-href="true" href="'.$url.'">'.$user->getLastName().' '. $user->getFirstName() . '</a> ('.$user->getPosition().') ';
            if ($this->getUser()->hasRole('ROLE_HRADMIN')) {
                $name .= '<a class="fa ajax-form fa-edit" title="'.$translator->trans('Change', array(), 'ListsComapnystructureBundle').'"
                           data-toggle="modal"
                           href="#form_modal"
                           data-target_holder="stuffFormTpl"
                           data-default=\'{"companystructureId":"'.$companystructureId.'"}\'
                           data-form_name="companystructureStuffForm"
                           data-post_function="updateList"
                           data-post_target_id="table_ajax"
                           ></a>';
            }
            $name .= '';
        } else {
            if ($this->getUser()->hasRole('ROLE_HRADMIN')) {
                $name .= ' | <a class="fa ajax-form fa-plus" title="'.$translator->trans('Specify director', array(), 'ListsComapnystructureBundle').'"
                           data-toggle="modal"
                           href="#form_modal"
                           data-target_holder="stuffFormTpl"
                           data-default=\'{"companystructureId":"'.$companystructureId.'"}\'
                           data-form_name="companystructureStuffForm"
                           data-post_function="updateList"
                           data-post_target_id="table_ajax"
                           ></a>';
            }
        }

        return $name;
    }
}
