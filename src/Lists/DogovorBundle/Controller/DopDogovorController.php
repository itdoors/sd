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

    /**
     * Executes new action
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('dogovorForm');

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $user = $this->getUser();

            /** @var \Lists\DogovorBundle\Entity\Dogovor $object */
            $object = $form->getData();

            $file = $form['file']->getData();

            if ($file)
            {
                $object->upload();
            }

            $object->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirect($this->generateUrl('lists_dogovor_dogovor_show', array(
                'id' => $object->getId()
            )));
        }

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':new.html.twig', array(
            'form' => $form->createView(),
            'filterFormName' => $this->filterFormName,
            'baseRoutePrefix' => $this->baseRoutePrefix,
            'baseTemplate' => $this->baseTemplate
        ));
    }

    /**
     * Executes show action
     */
    public function showAction($id)
    {
        /** @var \Lists\DogovorBundle\Entity\Dogovor $object */
        $object = $this->getDoctrine()
            ->getRepository('ListsDogovorBundle:Dogovor')
            ->find($id);

        return $this->render('ListsDogovorBundle:' . $this->baseTemplate . ':show.html.twig', array(
            'dogovor' => $object,
            'baseTemplate' => $this->baseTemplate,
            'baseRoutePrefix' => $this->baseRoutePrefix,
        ));
    }
}
