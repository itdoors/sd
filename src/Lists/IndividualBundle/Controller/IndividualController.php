<?php

namespace Lists\IndividualBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Lists\IndividualBundle\Entity\Individual;
use Lists\IndividualBundle\Form\IndividualType;

/**
 * Individual controller.
 *
 */
class IndividualController extends Controller
{

    /**
     * Lists all Individual entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ListsIndividualBundle:Individual')->findAll();

        return $this->render('ListsIndividualBundle:Individual:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Individual entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Individual();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('individual_show', array('id' => $entity->getId())));
        }

        return $this->render('ListsIndividualBundle:Individual:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Individual entity.
     *
     * @param Individual $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Individual $entity)
    {
        $form = $this->createForm(new IndividualType(), $entity, array(
            'action' => $this->generateUrl('individual_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Individual entity.
     *
     */
    public function newAction()
    {
        $entity = new Individual();
        $form   = $this->createCreateForm($entity);

        return $this->render('ListsIndividualBundle:Individual:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Individual entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ListsIndividualBundle:Individual')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Individual entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ListsIndividualBundle:Individual:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Individual entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ListsIndividualBundle:Individual')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Individual entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ListsIndividualBundle:Individual:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Individual entity.
    *
    * @param Individual $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Individual $entity)
    {
        $form = $this->createForm(new IndividualType(), $entity, array(
            'action' => $this->generateUrl('individual_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Individual entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ListsIndividualBundle:Individual')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Individual entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('individual_edit', array('id' => $id)));
        }

        return $this->render('ListsIndividualBundle:Individual:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Individual entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ListsIndividualBundle:Individual')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Individual entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('individual'));
    }

    /**
     * Creates a form to delete a Individual entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('individual_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
