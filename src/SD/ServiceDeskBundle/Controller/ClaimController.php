<?php

namespace SD\ServiceDeskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SD\ServiceDeskBundle\Entity\Claim;
use SD\ServiceDeskBundle\Form\ClaimType;
use SD\ServiceDeskBundle\Entity\ClaimMessage;

/**
 * Claim controller.
 *
 */
class ClaimController extends Controller
{

    /**
     * Lists all Claim entities.
     *
     * @return string
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em
            ->getRepository('SDServiceDeskBundle:Claim')
            ->findAll();

        return $this->render('SDServiceDeskBundle:Claim:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Adds message to the claim (via ajax).
     * 
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addMsgAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $claim_id = $request->get('claim_id');
        $text = $request->get('text');
        $createdAt = $request->get('createdAt'); 

        $claim = $em
            ->getRepository('SDServiceDeskBundle:Claim')
            ->find($claim_id);

        $message = new ClaimMessage();
        $message
            ->setClaim($claim)
            ->setText($text)
            ->setCreatedAt((new \DateTime())->setTimestamp($createdAt))
            ->setUser($this->getUser());

        $claim->addMessage($message);
        $em->persist($message);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Creates a new Claim entity.
     * 
     * @param Request $request
     *
     * @return string
     */
    public function createAction(Request $request)
    {
        $entity = new Claim();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('claim_show', array('id' => $entity->getId())));
        }

        return $this->render('SDServiceDeskBundle:Claim:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Claim entity.
     *
     * @param Claim $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Claim $entity)
    {
        $form = $this->createForm(new ClaimType(), $entity, array(
            'action' => $this->generateUrl('claim_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Claim entity.
     *
     * @return string
     */
    public function newAction()
    {
        $entity = new Claim();
        $form   = $this->createCreateForm($entity);

        return $this->render('SDServiceDeskBundle:Claim:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Claim entity.
     *
     * @param integer $id
     * 
     * @return string
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDServiceDeskBundle:Claim')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Claim entity.');
        }

        $messages = $em
            ->getRepository('SDServiceDeskBundle:ClaimMessage')
            ->findBy(
                array('claim' => $entity),
                array('createdAt' => 'DESC')
            );

        return $this->render('SDServiceDeskBundle:Claim:show.html.twig', array(
            'entity' => $entity,
            'messages' => $messages
        ));
    }

    /**
     * Displays a form to edit an existing Claim entity.
     * 
     * @param integer $id
     * 
     * @return string
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDServiceDeskBundle:Claim')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Claim entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('SDServiceDeskBundle:Claim:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a Claim entity.
    *
    * @param Claim $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Claim $entity)
    {
        $form = $this->createForm(new ClaimType(), $entity, array(
            'action' => $this->generateUrl('claim_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Claim entity.
     * 
     * @param Request $request
     * @param integer $id
     * 
     * @return string
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SDServiceDeskBundle:Claim')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Claim entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('claim', array('id' => $id)));
        }

        return $this->render('SDServiceDeskBundle:Claim:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView()
        ));
    }
    /**
     * Deletes a Claim entity.
     *
     * @param Request $request
     * @param integer $id
     * 
     * @return string
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SDServiceDeskBundle:Claim')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Claim entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('claim'));
    }

    /**
     * Creates a form to delete a Claim entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('claim_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * Saves uploaded image
     *
     * @param Request $request
     *
     * @return string path to image
     */
    public function uploadAction(Request $request)
    {
        $name = $this->randomString();
        $ext = explode('.', $_FILES['file']['name']);
        $directory = $this->container->getParameter('project.web.dir');
        $directory .= '/uploads/claim/images';
        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $ext = explode('.', $_FILES['file']['name']);
        $filename = $name . '.' . $ext[1];
        $destination = $directory . $filename;
        $location = $_FILES["file"]["tmp_name"];
        move_uploaded_file($location, $destination);
        $destination = '/uploads/claim/images' . $filename;

        return new Response($destination);
    }

    /**
     * Random string
     *
     * @return string
     */
    private function randomString()
    {
        return md5(rand(100, 200));
    }
}
