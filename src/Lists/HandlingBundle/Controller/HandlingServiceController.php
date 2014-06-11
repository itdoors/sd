<?php

namespace Lists\HandlingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Lists\HandlingBundle\Entity\HandlingService;
use Lists\HandlingBundle\Form\HandlingServiceType;

/**
 * HandlingService controller.
 *
 */
class HandlingServiceController extends Controller
{

    /**
     * Lists all HandlingService entities.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ListsHandlingBundle:HandlingService')->findAll();

        return $this->render('ListsHandlingBundle:HandlingService:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new HandlingService entity.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new HandlingService();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('handlingservice_show', array('id' => $entity->getId())));
        }

        return $this->render('ListsHandlingBundle:HandlingService:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a HandlingService entity.
    *
    * @param HandlingService $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(HandlingService $entity)
    {
        $form = $this->createForm(new HandlingServiceType(), $entity, array(
            'action' => $this->generateUrl('handlingservice_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new HandlingService entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $entity = new HandlingService();
        $form   = $this->createCreateForm($entity);

        return $this->render('ListsHandlingBundle:HandlingService:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a HandlingService entity.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ListsHandlingBundle:HandlingService')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HandlingService entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ListsHandlingBundle:HandlingService:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing HandlingService entity.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ListsHandlingBundle:HandlingService')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HandlingService entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ListsHandlingBundle:HandlingService:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a HandlingService entity.
    *
    * @param HandlingService $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(HandlingService $entity)
    {
        $form = $this->createForm(new HandlingServiceType(), $entity, array(
            'action' => $this->generateUrl('handlingservice_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing HandlingService entity.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ListsHandlingBundle:HandlingService')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find HandlingService entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('handlingservice_edit', array('id' => $id)));
        }

        return $this->render('ListsHandlingBundle:HandlingService:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a HandlingService entity.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ListsHandlingBundle:HandlingService')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find HandlingService entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('handlingservice'));
    }

    /**
     * Creates a form to delete a HandlingService entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('handlingservice_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }
}
