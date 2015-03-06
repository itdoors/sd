<?php

namespace SD\BusinessRoleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SD\BusinessRoleBundle\Entity\CompanyClient;
use SD\BusinessRoleBundle\Form\CompanyClientType;

/**
 * CompanyClient controller.
 *
 */
class CompanyClientController extends Controller
{

    /**
     * Lists all CompanyClient entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SDBusinessRoleBundle:CompanyClient')->findAll();

        return $this->render('SDBusinessRoleBundle:CompanyClient:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CompanyClient entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CompanyClient();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('companyclient_show', array('id' => $entity->getId())));
        }

        return $this->render('SDBusinessRoleBundle:CompanyClient:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a CompanyClient entity.
     *
     * @param CompanyClient $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CompanyClient $entity)
    {
        $form = $this->createForm(new CompanyClientType(), $entity, array(
            'action' => $this->generateUrl('companyclient_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new CompanyClient entity.
     *
     */
    public function newAction()
    {
        $entity = new CompanyClient();
        $form   = $this->createCreateForm($entity);

        return $this->render('SDBusinessRoleBundle:CompanyClient:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CompanyClient entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDBusinessRoleBundle:CompanyClient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompanyClient entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SDBusinessRoleBundle:CompanyClient:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing CompanyClient entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDBusinessRoleBundle:CompanyClient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompanyClient entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SDBusinessRoleBundle:CompanyClient:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CompanyClient entity.
    *
    * @param CompanyClient $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CompanyClient $entity)
    {
        $form = $this->createForm(new CompanyClientType(), $entity, array(
            'action' => $this->generateUrl('companyclient_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CompanyClient entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDBusinessRoleBundle:CompanyClient')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CompanyClient entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('companyclient_edit', array('id' => $id)));
        }

        return $this->render('SDBusinessRoleBundle:CompanyClient:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CompanyClient entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SDBusinessRoleBundle:CompanyClient')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CompanyClient entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('companyclient'));
    }

    /**
     * Creates a form to delete a CompanyClient entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('companyclient_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
