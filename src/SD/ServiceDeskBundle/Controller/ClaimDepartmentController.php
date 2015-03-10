<?php

namespace SD\ServiceDeskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SD\ServiceDeskBundle\Entity\ClaimDepartment;
use SD\ServiceDeskBundle\Form\ClaimDepartmentType;
use SD\ServiceDeskBundle\Entity\StatusType;
use SD\ServiceDeskBundle\Entity\FinStatusType;

/**
 * ClaimDepartment controller.
 *
 */
class ClaimDepartmentController extends Controller
{

    /**
     * Lists all ClaimDepartment entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SDServiceDeskBundle:ClaimDepartment')->findAll();

        return $this->render('SDServiceDeskBundle:ClaimDepartment:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new ClaimDepartment entity.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $request->request->get('sd_servicedeskbundle_claimdepartment');
        $em = $this->getDoctrine()->getManager();
        $entity = new ClaimDepartment();
        $f = $this->createCreateForm($entity);
        $f->handleRequest($request);

        $targetDepartment = $em->getRepository('ListsDepartmentBundle:Departments')->find($form['targetDepartment']);
        $mpks = $targetDepartment->getMpks();
        foreach ($mpks as $mpk) {
            if ($mpk->getActive()) {
                $entity->setMpk($mpk->getName());
                break;
            }
        }

        $entity->setTargetDepartment($targetDepartment);
        $entity->setComeTime(new \DateTime($form['comeTime']));
        $entity->setCreatedAt(new \DateTime());
        $entity->setStatus(StatusType::OPEN);
        $entity->setFinStatus(FinStatusType::OPENED);
        $entity->setCustomer($em->getRepository('SDBusinessRoleBundle:CompanyClient')->find($form['customer']));
        $entity->setImportance($em->getRepository('SDServiceDeskBundle:ClaimImportance')->find($form['importance']));
        $entity->setType($form['type']);
        $entity->setText($form['text']);

        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('claim_show', array('id' => $entity->getId())));
    }

    /**
     * Creates a form to create a ClaimDepartment entity.
     *
     * @param ClaimDepartment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ClaimDepartment $entity)
    {
        $form = $this->createForm(new ClaimDepartmentType($this->container), $entity, array(
            'action' => $this->generateUrl('claimdepartment_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new ClaimDepartment entity.
     *
     */
    public function newAction()
    {
        $entity = new ClaimDepartment();
        $form   = $this->createCreateForm($entity);

        return $this->render('SDServiceDeskBundle:ClaimDepartment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ClaimDepartment entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDServiceDeskBundle:ClaimDepartment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ClaimDepartment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SDServiceDeskBundle:ClaimDepartment:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ClaimDepartment entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDServiceDeskBundle:ClaimDepartment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ClaimDepartment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SDServiceDeskBundle:ClaimDepartment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ClaimDepartment entity.
    *
    * @param ClaimDepartment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ClaimDepartment $entity)
    {
        $form = $this->createForm(new ClaimDepartmentType(), $entity, array(
            'action' => $this->generateUrl('claimdepartment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ClaimDepartment entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDServiceDeskBundle:ClaimDepartment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ClaimDepartment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('claimdepartment_edit', array('id' => $id)));
        }

        return $this->render('SDServiceDeskBundle:ClaimDepartment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ClaimDepartment entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SDServiceDeskBundle:ClaimDepartment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ClaimDepartment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('claimdepartment'));
    }

    /**
     * Creates a form to delete a ClaimDepartment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('claimdepartment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
