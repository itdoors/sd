<?php

namespace SD\CalendarBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Doctrine\ORM\EntityManager;
use SD\CalendarBundle\Entity\Holiday;

/**
 * Class HolidayController
 */
class HolidayController extends BaseFilterController
{
    protected $baseTemplate = 'Holiday';
    protected $namespace = 'sd.holiday';
    protected $paginator = 'knp_paginator';

    /**
     * Renders template holder for calendar
     *
     * @return string
     */
    public function indexAction()
    {
        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':index.html.twig');
    }
    /**
     *  listAction
     * 
     * @return render Description
     */
    public function listAction ()
    {
        $namespace = $this->container->getParameter($this->namespace) . 'list';
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Holiday $params */
        $page = $this->getPaginator($namespace);
        if (!$page) {
            $page = 1;
        }

        $result = $em
            ->getRepository('SDCalendarBundle:Holiday')
            ->getList();
        $count = $result['count'];
        $entities = $result['entities'];
        

        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $count);
        $pagination = $paginator->paginate($entities, $page, 10);


        return $this->render('SDCalendarBundle:' . $this->baseTemplate . ':list.html.twig', array (
                'entities' => $pagination
        ));
    }
}
