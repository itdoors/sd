<?php

namespace SD\TaskBundle\Services;

use Doctrine\ORM\EntityManager;
use Lists\DogovorBundle\Entity\Dogovor;
use Lists\DogovorBundle\Entity\DogovorRepository;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingDogovor;
use Lists\HandlingBundle\Entity\HandlingRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * HandlingDogovorService class
 */
class TaskFileService
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct()
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Add form defaults depending on defaults)
     *
     * @param Form    $form
     * @param mixed[] $defaults
     */
    public function addFormDefaults(Form $form, $defaults)
    {


    }

    /**
     * Save form
     *
     * @param Form    $form
     * @param Request $request
     * @param mixed[] $params
     */
    public function saveForm(Form $form, Request $request, $params)
    {
        $data = $form->getData();

        $formData = $request->request->get($form->getName());

        $idTask = $params['parameters']['idTask'];
        $task =  $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:Task')->find($idTask);

        $data->setCreateDate(new \DateTime());
        $data->setTask($task);

        $user = $this->container->get('security.context')->getToken()->getUser();

        $data->setUser($user);

        $file = $form['file']->getData();

        if ($file) {
            $data->upload();
        }

        $em = $this->container->get('doctrine')->getManager();
        $em->persist($data);
        $em->flush();

        //comment to uploaded file
        $taskUserRole = $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
                'task' => $task,
                'user' => $user
            ));
        $taskService = $this->container->get('task.service');
        $translator = $this->container->get('translator');
        $commentValue = $translator->trans('Uploaded file', array(), 'SDTaskBundle').': '.$data->getName();
        $taskService->insertCommentToTask($taskUserRole->getId(), $commentValue);



        $roleRepository = $em->getRepository('SDTaskBundle:Role');

        $controllerRole = $roleRepository
            ->findOneBy(array(
                'name' => 'controller',
                'model' => 'task'
            ));

        $taskUserRoleController = $em->getRepository('SDTaskBundle:TaskUserRole')->findOneBy(array(
            'task' => $task,
            'role' => $controllerRole
        ));
        //sending file to controller role
        $userController = $taskUserRoleController->getUser();
        if ($userController != $user && $task->getStage() != 'matching') {
            $taskService = $this->container->get('task.service');
            $info = array('fileName' => $data->getName());
            $taskService->sendEmailInform(array($taskUserRoleController), 'new_file', $info);
        }

    }
}
