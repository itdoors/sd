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
 * TaskService class
 */
class TaskService
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
     *
     * @return bool
     */
    public function saveForm(Form $form, Request $request, $params)
    {
        /** @var \SD\TaskBundle\Entity\Task $data */
        $data = $form->getData();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $formData = $request->request->get($form->getName());

        $data->setCreateDate(new \DateTime());
        $data->setAuthor($user);
        $data->setStartDate(new \DateTime($formData['startDate']));

        $stage = $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:Stage')
            ->findOneBy(array(
                'model' =>'task',
                'name' => 'created'
            ));

        $data->setStage($stage);

        $em = $this->container->get('doctrine')->getManager();
        $em->persist($data);
        $em->flush();
        //$em->refresh($data);


        $file = $form['files']->getData();
        if ($file) {
            $fileTask = new \SD\TaskBundle\Entity\TaskFile();
            $fileTask->setTask($data);
            $fileTask->setUser($user);
            $fileTask->setCreateDate(new \DateTime());
            $fileTask->setFile($file);
            $fileTask->upload();
            $em->persist($fileTask);
            $em->flush();
        }

        $dateStage = $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:Stage')
            ->findOneBy(array(
                'model' =>'task_end_date',
                'name' => 'accepted'
            ));

        $endDate = new \SD\TaskBundle\Entity\TaskEndDate();
        $endDate->setTask($data);
        $endDate->setChangeDateTime(new \DateTime());
        $endDate->setEndDateTime(new \DateTime($formData['endDate']));
        $endDate->setStage($dateStage);
        $em->persist($endDate);

        $userRepository = $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDUserBundle:User');

        $roleRepository = $this->container
            ->get('doctrine')->getManager()
            ->getRepository('SDTaskBundle:Role');

        $performerRole = $roleRepository
            ->findOneBy(array(
                'name' => 'performer',
                'model' => 'task'
            ));

        $controllerRole  = $roleRepository
            ->findOneBy(array(
                'name' => 'controller',
                'model' => 'task'
            ));

        $authorRole  = $roleRepository
            ->findOneBy(array(
                'name' => 'author',
                'model' => 'task'
            ));

        $taskUserRole = new \SD\TaskBundle\Entity\TaskUserRole();
        $taskUserRole->setRole($authorRole);
        $taskUserRole->setUser($user);
        $taskUserRole->setTask($data);
        $taskUserRole->setIsViewed(true);
        $em->persist($taskUserRole);

        //var_dump($formData['performer']);die();
        //foreach ($formData['performer'] as $performer) {
        $idPerformer = $formData['performer'];
        $performer = $userRepository->find($idPerformer);

        $taskUserRole = new \SD\TaskBundle\Entity\TaskUserRole();
        $taskUserRole->setRole($performerRole);
        $taskUserRole->setUser($performer);
        $taskUserRole->setTask($data);
        $taskUserRole->setIsViewed(false);
        $em->persist($taskUserRole);
        //}

        //foreach ($formData['controller'] as $idController) {
        $idController = $formData['controller'];
        $controller = $userRepository->find($idController);

        $taskUserRole = new \SD\TaskBundle\Entity\TaskUserRole();
        $taskUserRole->setRole($controllerRole);
        $taskUserRole->setUser($controller);
        $taskUserRole->setTask($data);
        $taskUserRole->setIsViewed(false);
        $em->persist($taskUserRole);
        //}
        $em->flush();

        return true;
    }

    /**
     * sendEmailInform
     *
     * @param SD/TaskBundle/Entity/TaskUserRole[] $taskUserRoles
     * @param string                              $type
     */
    public function sendEmailInform($taskUserRoles, $type)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        $translator = $this->container->get('translator');
        $templating = $this->container->get('templating');
        $emailService = $this->container->get('it_doors_email.service');

        if ($type == 'new') {
            $subject = $translator->trans('You have new task', array(), 'SDTaskBundle');

            foreach ($taskUserRoles as $taskUserRole) {
                $text = '<b>'.$translator->trans('You have new task', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Your role', array (), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getRole(), array(), 'SDTaskBundle');

                $emails = array();
                $userEmail = $taskUserRole->getUser()->getEmail();
                if ($userEmail) {
                    $emails[] = $userEmail;
                }

                $emailService->send(
                    null,
                    'empty-template',
                    array (
                        'users' => $emails,
                        'variables' => array (
                            '${subject}$' => $subject,
                            '${text}$' => $text
                        )
                    )
                );

            }

        } elseif ($type == 'close') {
            $subject = $translator->trans('Task had been finished', array(), 'SDTaskBundle');

            foreach ($taskUserRoles as $taskUserRole) {
                $text = '<b>'.$translator->trans('Task had been finished', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Your role', array(), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getRole(), array(), 'SDTaskBundle');
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Final stage', array(), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getTask()->getStage(), array(), 'SDTaskBundle');

                $emails = array();
                $userEmail = $taskUserRole->getUser()->getEmail();
                if ($userEmail) {
                    $emails[] = $userEmail;
                }

                $emailService->send(
                    null,
                    'empty-template',
                    array (
                        'users' => $emails,
                        'variables' => array (
                            '${subject}$' => $subject,
                            '${text}$' => $text
                        )
                    )
                );

            }
        }

        $cron = $this->container->get('it_doors_cron.service');
        $cron->addSendEmails();
    }
}
