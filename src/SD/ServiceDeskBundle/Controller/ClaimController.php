<?php

namespace SD\ServiceDeskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SD\ServiceDeskBundle\Entity\Claim;
use SD\ServiceDeskBundle\Form\ClaimType;
use SD\ServiceDeskBundle\Form\ClaimMessageType;
use SD\ServiceDeskBundle\Entity\ClaimMessage;
use SD\ServiceDeskBundle\Entity\ClaimPerformerRule;
use SD\ServiceDeskBundle\Form\PerformerRuleForm;
use SD\ServiceDeskBundle\Entity\StatusType;
use SD\BusinessRoleBundle\Entity\Client;
use Lists\IndividualBundle\Entity\Individual;
use SD\ServiceDeskBundle\Entity\ClaimOnce;

/**
 * Claim controller.
 *
 */
class ClaimController extends Controller
{
    /**
     * Lists archive Claim entities.
     *
     * @return string
     */
    public function archiveIndexAction()
    {
        $em = $this->getDoctrine()->getManager();
    
        $entities = $em
            ->getRepository('SDServiceDeskBundle:Claim')
            ->findAll();
        
        $result = [];
        foreach ($entities as $entity) {
            $result[] = [
                'claim' => $entity,
                'firstName' => $entity->getCustomer() ? $entity->getCustomer()->getIndividual()->getFirstName() : '',
                'lastName' => $entity->getCustomer() ? $entity->getCustomer()->getIndividual()->getLastName() : ''
            ];
        }

        return $this->render('SDServiceDeskBundle:Claim:index.html.twig', array(
            'entities' => $result
        ));
    }

    /**
     * Lists all not finished Claim entities.
     *
     * @return string
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities1 = $em
            ->getRepository('SDServiceDeskBundle:ClaimOnce')
            ->findNotDone();

        $entities2 = $em
            ->getRepository('SDServiceDeskBundle:ClaimDepartment')
            ->findNotDone();

        return $this->render('SDServiceDeskBundle:Claim:index.html.twig', array(
            'entities' => array_merge($entities1, $entities2)
        ));
    }

    /**
     * Changes claim's status (via ajax).
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changeStatusAction(Request $request)
    {
        $id = $request->get('pk');
        $status = $request->get('value');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SDServiceDeskBundle:Claim')->find($id);
        $entity->setStatus($status);
        
        $em->persist($entity);
        $em->flush();
    
        return new JsonResponse();
    }

    /**
     * Changes claim's type (via ajax).
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changeTypeAction(Request $request)
    {
        $id = $request->get('pk');
        $type = $request->get('value');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SDServiceDeskBundle:Claim')->find($id);
        $entity->setType($type);

        $em->persist($entity);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Changes claim's importance (via ajax).
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function changeImportanceAction(Request $request)
    {
        $id = $request->get('pk');
        $importance_id = $request->get('value');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SDServiceDeskBundle:Claim')->find($id);
        $entity->setImportance($em->getRepository('SDServiceDeskBundle:ClaimImportance')->find($importance_id));

        $em->persist($entity);
        $em->flush();
    
        return new JsonResponse();
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
        $entity = (new ClaimMessage())
            ->setCreatedAt(new \DateTime())
            ->setUser($this->getUser());

        $form = $this->createMessageForm($entity);
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        $response = [];
        $response['userLink'] = $this
            ->get('router')
            ->generate('sd_user_show', array(
                'id' => $entity->getUser()->getId()
            ));
        $response['staffOnly'] = $entity->getStaffOnly();
        $response['user'] = $entity->getUser()->__toString();
        $response['createdAt'] = $entity->getCreatedAt()->getTimestamp();
        foreach ($entity->getFiles() as $file) {
            $response['files'][] = [
                'name' => $file->getOriginName(),
                'description' => $file->getDescription() ? $file->getDescription() : '',
                'link' => $file->getLink()
            ];
        }

        return new JsonResponse($response);
    }

    /**
     * Adds performerRule to the claim (via ajax).
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addRuleAction(Request $request)
    {
        $entity = (new ClaimPerformerRule())->setClaim(new Claim());

        $form = $this->createPerformerRuleForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        $response = [];
        $response['id'] = $entity->getId();
        $response['performer_id'] = $entity->getClaimPerformer()->getId();
        $response['performer'] = $entity->getClaimPerformer()->__toString();

        return new JsonResponse($response);
    }

    /**
     * Adds performerRule to the claim (via ajax).
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeRuleAction(Request $request)
    {
        $id = $request->get('rule_id');

        $em = $this->getDoctrine()->getManager();
        $entity = $em
            ->getRepository('SDServiceDeskBundle:ClaimPerformerRule')
            ->find($id);

        if ($entity) {
            $em->remove($entity);
            $em->flush();

            return new JsonResponse();
        }

        return new JsonResponse(null, 500);
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
        $entity = new ClaimOnce();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        $entity->setStatus(StatusType::OPEN);
        $entity->setCreatedAt(new \DateTime());

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
     * Creates a form to create a ClaimMessage entity.
     *
     * @param ClaimMessage $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createMessageForm(ClaimMessage $entity)
    {
        $form = $this->createForm(new ClaimMessageType(), $entity, array(
            'action' => $this->generateUrl('claim_add_msg'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Creates a form to create a ClaimPerformerRule entity.
     *
     * @param ClaimPerformerRule $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createPerformerRuleForm(ClaimPerformerRule $entity)
    {
        $form = $this->createForm(new PerformerRuleForm($entity->getClaim()), $entity, array(
            'action' => $this->generateUrl('claim_add_rule'),
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
        $newClient = (new Client())->setIndividual(new Individual());
        $newClientForm = $this->createForm('clientAddForm', $newClient, array(
            'action' => $this->generateUrl('client_create'),
            'method' => 'POST',
        ));

        $newClientForm->add('individual', new \Lists\IndividualBundle\Form\IndividualType());

        return $this->render('SDServiceDeskBundle:Claim:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'indForm' => $newClientForm->createView()
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

        $entity = $em->getRepository('SDServiceDeskBundle:Claim')->findClaim($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Claim entity.');
        }

        $messages = $em
            ->getRepository('SDServiceDeskBundle:ClaimMessage')
            ->findByClaim($entity);

        $messageForm = $this->createMessageForm((new ClaimMessage())->setClaim($entity));
        $performerRuleForm = $this->createPerformerRuleForm((new ClaimPerformerRule())->setClaim($entity));
        
        $statuses = [];
        foreach (\SD\ServiceDeskBundle\Entity\StatusType::values() as $value) {
            $statuses[] = [
                'value' => $value,
                'text' => $this->get('translator')->trans($value)
            ];
        }

        $types = [];
        foreach (\SD\ServiceDeskBundle\Entity\ClaimType::values() as $value) {
            $types[] = [
                'value' => $value,
                'text' => $this->get('translator')->trans($value)
            ];
        }

        $importances = [];
        foreach ($em->getRepository('SDServiceDeskBundle:ClaimImportance')->findAll() as $value) {
            $importances[] = [
                'value' => $value->getId(),
                'text' => $value->getName()
            ];
        }

        return $this->render('SDServiceDeskBundle:Claim:show.html.twig', array(
            'entity' => $entity,
            'form' => $messageForm->createView(),
            'performerRuleForm' => $performerRuleForm->createView(),
            'importances' => json_encode($importances),
            'statuses' => json_encode($statuses),
            'types' => json_encode($types),
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
        $form->remove('files');

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

            return $this->redirect($this->generateUrl('claim_show', array('id' => $id)));
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
