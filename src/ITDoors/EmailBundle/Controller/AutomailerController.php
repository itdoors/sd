<?php

namespace ITDoors\EmailBundle\Controller;

use TSS\AutomailerBundle\Entity\Automailer;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use ITDoors\AjaxBundle\Controller\BaseFilterController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ITDoors\EmailBundle\Form\AutomailerType;
use ITDoors\EmailBundle\Entity\File;
use ITDoors\EmailBundle\Form\FileType;
use Swift_Message;
use Swift_Attachment;

/**
 * AutomailerController
 */
class AutomailerController extends BaseFilterController
{

    /** @var email $filterNamespace */
    protected $filterNamespace = 'it.doors.email.namespace';

    /** @var KnpPaginatorBundle $paginator */
    protected $paginator = 'knp_paginator';

    /**
     * Lists all Automailer entities.
     *
     * @Route("/email/history", name="it_doors_email_history")
     * @Template("ITDoorsEmailBundle:Automailer:index.html.twig")
     * 
     * @return Render
     */
    public function indexAction()
    {

        return $this->render('ITDoorsEmailBundle:Automailer:index.html.twig');
    }

    /**
     * Lists all Automailer entities.
     *
     * @Template("ITDoorsEmailBundle:Automailer:list.html.twig")
     * 
     * @return render
     */
    public function listAction()
    {
        $filterNamespace = $this->container->getParameter($this->getNamespace());

        $em = $this->getDoctrine()->getManager();

        $page = $this->getPaginator($filterNamespace);
        if (!$page) {
            $page = 1;
        }

//        $entities = $em->getRepository('TSSAutomailerBundle:Automailer')->findAll();

        $query = $em->createQueryBuilder('a');
        $entities = $query
            ->select('a.id')
            ->addselect('a.fromEmail')
            ->addselect('a.fromName')
            ->addselect('a.toEmail')
            ->addselect('a.subject')
            ->addselect('a.body')
            ->addselect('a.createdAt')
            ->addselect('a.sentAt')
            ->addselect('a.isHtml')
            ->addselect('a.isSent')
            ->addselect('a.isSending')
            ->addselect('a.isFailed')
            ->from('TSSAutomailerBundle:Automailer', 'a')
            ->getQuery();


        $total = $query->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();


        $paginator = $this->container->get($this->paginator);
        $entities->setHint($this->paginator . '.count', $total);
        $pagination = $paginator->paginate($entities, $page, 5);

        return $this->render('ITDoorsEmailBundle:Automailer:list.html.twig', array(
                'entities' => $pagination
        ));
    }

    /**
     * Finds and displays a Automailer entity.
     *
     * @param integer $id Description
     * 
     * @Route("/email/show/{id}", name="automailer_show")
     * @Template("ITDoorsEmailBundle:Automailer:show.html.twig")
     * 
     * @return array
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TSSAutomailerBundle:Automailer')->find($id);

        $files = $em->getRepository('ITDoorsEmailBundle:File')
            ->findBy(array(
                'tableName' => 'automailer',
                'tableId' => $id
            ));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Automailer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'files' => $files,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Automailer entity.
     *
     * @Route("/email/new", name="automailer_new")
     * @Template("ITDoorsEmailBundle:Automailer:new.html.twig")
     * 
     * @return array
     */
    public function newAction()
    {
        $entity = new Automailer();
        $form = $this->createForm(new AutomailerType(), $entity);

        $session = $this->get('session');
        $filesName = json_decode($session->get('files_upload', '{}'), true);
        $filesLoad = array();
        $directoryTemp = $this->container->getParameter('upload.file.path');
        foreach ($filesName as $name => $nameOrig) {
            $filesLoad[] = array(
                'name' => $name,
                'nameOrig' => $nameOrig,
                'size' => filesize($directoryTemp.'/'.$name),
                'type' => mime_content_type($directoryTemp.'/'.$name)
            );
        }

        return array(
            'filesLoad' => $filesLoad,
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new Automailer entity.
     *
     * @Route("/email/create", name="automailer_create")
     * @Method("post")
     * @Template("ITDoorsEmailBundle:Automailer:new.html.twig")
     * 
     * @return array
     */
    public function createAction()
    {
        $entity = new Automailer();
        $form = $this->createForm(new AutomailerType(), $entity);
        $form->bind($this->getRequest());

        $em = $this->getDoctrine()->getManager();

        $files = new File();
        $filesForm = $this->createForm(new FileType(), $files);
        $filesForm->bind($this->getRequest());

        if ($filesForm->isValid()) {
            return true;
        }

        if ($form->isValid()) {

            /** Swift_Mailer $mailer */
            $mailer = $this->container->get('mailer');

            $message = Swift_Message::newInstance()
                ->setSubject($entity->getSubject())
                ->setFrom(array($entity->getFromEmail() => $entity->getFromName()))
                ->setTo($entity->getToEmail())
                ->setBody($entity->getBody(), 'text/html');

            $session = $this->get('session');
            $filesArr = json_decode($session->get('files_upload', '{}'), true);

            $directoryTemp = $this->container->getParameter('upload.file.path');
            $directory = $this->container->getParameter('email.file.path');
            foreach ($filesArr as $key => $file) {
                if (is_file($directoryTemp.'/' . $key)) {
                    rename($directoryTemp.'/' . $key, $directory .'/'. $key);
                    $message->attach(Swift_Attachment::fromPath($directory .'/'. $key));
                    $session->remove('files_upload');
                } else {
                    echo $directoryTemp.'/'. $key;
                    die;
                }
            }
            $mailer->send($message);

            $id = $this->getDoctrine()->getRepository('TSSAutomailerBundle:Automailer')
                ->findOneBy(array(), array('id' => 'DESC'))
                ->getId();

            foreach ($filesArr as $key => $file) {
                if (is_file($directory .'/'. $key)) {
                    $addFile = new File();
                    $addFile->setName($key);
                    $addFile->setPath($directory);
                    $addFile->setTableName('automailer');
                    $addFile->setTableId($id);
                    $addFile->setUserId($this->getUser()->getId());
                    $addFile->setDate(new \DateTime(date('Y-m-d H:i:s')));
                    $em->persist($addFile);
                }
            }
            $em->flush();

            return $this->redirect($this->generateUrl('automailer_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Deletes a Automailer entity.
     *
     * @param integer $id
     * 
     * @Route("/email/delete/{id}", name="automailer_delete")
     * @Method("post")
     * 
     * @return redirect
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);

        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TSSAutomailerBundle:Automailer')->find($id);

            $files = $em->getRepository('ITDoorsEmailBundle:File')
            ->findBy(array(
                'tableName' => 'automailer',
                'tableId' => $id
            ));

            foreach ($files as $file) {
                if (is_file($file->getPath() . $file->getName())) {
                    @unlink($file->getPath() . $file->getName());
                }
                $em->remove($file);
            }

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Automailer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('it_doors_email_history'));
    }

    private function createDeleteForm($id)
    {

        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
        ;
    }
}
