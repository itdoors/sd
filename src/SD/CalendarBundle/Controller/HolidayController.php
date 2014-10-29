<?php

namespace SD\CalendarBundle\Controller;

use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Doctrine\ORM\EntityManager;
use SD\CalendarBundle\Entity\Holiday;
use Symfony\Component\HttpFoundation\Response;

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
    /**
     * Saves object to db
     *
     * @return mixed[]
     */
    public function holidaySaveAction()
    {
        $translator = $this->get('translator');

        $pk = $this->get('request')->request->get('pk');
        $name = $this->get('request')->request->get('name');
        $value = $this->get('request')->request->get('value');
        /** @var Holiday $object */
        $object = $this->getDoctrine()
            ->getRepository('SDCalendarBundle:Holiday')
            ->find($pk);

        if ($name == 'date') {
            $value = new \DateTime($value.'.'.date('Y'));
        }
        $methodSet = 'set' . ucfirst($name);

        $object->$methodSet($value);

        $validator = $this->get('validator');

        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($object, array('edit'));

        if (sizeof($errors)) {
            $return = $this->getFirstError($errors);

            return new Response($return, 406);
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);

        try {
            $em->flush();
        } catch (\ErrorException $e) {
            $return = array('msg' => $translator->trans('Wrong input data'));

            return new Response(json_encode($return));
        }

        $return = array('success' => 1);

        return new Response(json_encode($return));
    }
}
