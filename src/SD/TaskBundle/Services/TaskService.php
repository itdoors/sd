<?php

namespace SD\TaskBundle\Services;

use Doctrine\ORM\EntityManager;
use Lists\DogovorBundle\Entity\Dogovor;
use Lists\DogovorBundle\Entity\DogovorRepository;
use Lists\HandlingBundle\Entity\Handling;
use Lists\HandlingBundle\Entity\HandlingDogovor;
use Lists\HandlingBundle\Entity\HandlingRepository;
use SD\TaskBundle\Entity\Comment;
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
     * @param int    $idTaskUserRole
     * @param string $commentValue
     * @param string $model
     */
    public function insertCommentToTask($idTaskUserRole, $commentValue, $model = 'Task', $additional = null)
    {
        $em = $this->container->get('doctrine')->getManager();
        $taskUserRole = $em->getRepository('SDTaskBundle:TaskUserRole')->find($idTaskUserRole);

        $idTask = $taskUserRole->getTask()->getId();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $comment = new Comment();

        $comment->setValue($commentValue);
        $comment->setCreateDatetime(new \DateTime());
        $comment->setModel($model);
        $comment->setModelId($idTask);
        $comment->setUser($user);
        $comment->setAdditionField($additional);

        $em->persist($comment);
        $em->flush();
    }


    /**
     * sendEmailInform
     *
     * @param SD/TaskBundle/Entity/TaskUserRole[] $taskUserRoles
     * @param string                              $type
     * @param array                               $additionalInfo
     */
    public function sendEmailInform($taskUserRoles, $type, $additionalInfo = null)
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
                $text .= "<br>";

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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
        } elseif ($type == 'resolution') {
            $subject = $translator->trans('You got resolution', array(), 'SDTaskBundle');

            foreach ($taskUserRoles as $taskUserRole) {
                $text = '<b>'.$translator->trans('You got resolution to task', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Your role', array(), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getRole(), array(), 'SDTaskBundle');
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Resolution', array(), 'SDTaskBundle').':</b> ';
                $text .= $additionalInfo['resolution'];

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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

        } elseif ($type == 'matching') {
            $subject = $translator->trans('New matching result', array(), 'SDTaskBundle');

            foreach ($taskUserRoles as $taskUserRole) {
                $text = '<b>'.$translator->trans('Task', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";

                $text .= '<b>'.$translator->trans('New matching result', array(), 'SDTaskBundle').':</b> ';
                if ($additionalInfo['status']) {
                    $statusText = 'Signed up';
                } else {
                    $statusText = 'Refused sign up';
                }
                $text .= $translator->trans($statusText, array(), 'SDTaskBundle');
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Your role', array(), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getRole(), array(), 'SDTaskBundle');
                $text .= "<br>";
                if ($additionalInfo['message']) {
                    $text .= '<b>'.$translator->trans('Comment', array(), 'SDTaskBundle').':</b> ';
                    $text .= $additionalInfo['message'];
                }

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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

        } elseif ($type == 'date_request') {
            $subject = $translator->trans('New date request', array(), 'SDTaskBundle');

            foreach ($taskUserRoles as $taskUserRole) {
                $text = '<b>'.$translator->trans('Task', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";

                $text .= '<b>'.$translator->trans('Requested date', array(), 'SDTaskBundle').':</b> ';

                $text .= $additionalInfo['date']->format('d-m-Y H:i');
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Your role', array(), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getRole(), array(), 'SDTaskBundle');
                $text .= "<br>";
                if ($additionalInfo['message']) {
                    $text .= '<b>'.$translator->trans('Comment', array(), 'SDTaskBundle').':</b> ';
                    $text .= $additionalInfo['message'];
                }

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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

        } elseif ($type == 'changing_date') {
            $subject = $translator->trans('New date end', array(), 'SDTaskBundle');

            foreach ($taskUserRoles as $taskUserRole) {
                $text = '<b>'.$translator->trans('Task', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";

                $text .= '<b>'.$translator->trans('New date end', array(), 'SDTaskBundle').':</b> ';

                $text .= $additionalInfo['date']->format('d-m-Y H:i');
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Your role', array(), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getRole(), array(), 'SDTaskBundle');
                $text .= "<br>";
                if ($additionalInfo['message']) {
                    $text .= '<b>'.$translator->trans('Comment', array(), 'SDTaskBundle').':</b> ';
                    $text .= $additionalInfo['message'];
                }

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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

        } elseif ($type == 'new_file') {
            $subject = $translator->trans('New file uploaded', array(), 'SDTaskBundle');

            foreach ($taskUserRoles as $taskUserRole) {
                $text = '<b>'.$translator->trans('Task', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";

                $text .= '<b>'.$translator->trans('File name', array(), 'SDTaskBundle').':</b> ';

                $text .= $additionalInfo['fileName'];
                $text .= "<br>";
                $text .= '<b>'.$translator->trans('Your role', array(), 'SDTaskBundle').':</b> ';
                $text .= $translator->trans($taskUserRole->getRole(), array(), 'SDTaskBundle');
                $text .= "<br>";

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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

    /**
     * @return void
     */
    public function informEndTask()
    {
        $em = $this->container->get('doctrine')->getManager();

        $taskUserRoleRepo = $em->getRepository('SDTaskBundle:TaskUserRole');
        $taskEndDateRepo = $em->getRepository('SDTaskBundle:TaskEndDate');

        $stageAcceptedDate = $em->getRepository('SDTaskBundle:Stage')
            ->findBy(
                array(
                    'name' => 'accepted',
                    'model' => 'task_end_date'
                ),
                array(
                    'id' => 'DESC'
                )
            );

        $informTaskUserRoles = $taskUserRoleRepo->getInformEntities();

        $translator = $this->container->get('translator');
        $emailService = $this->container->get('it_doors_email.service');

        $subject = $translator->trans('Task end date soon', array(), 'SDTaskBundle');

        if (count($informTaskUserRoles)) {
            foreach ($informTaskUserRoles as $taskUserRole) {
                $dateEnd = $taskEndDateRepo->findOneBy(
                    array(
                        'task' => $taskUserRole->getTask(),
                        'stage' => $stageAcceptedDate
                    ),
                    array(
                        'id' => 'DESC'
                    )
                );
                $text = '<i>'.$translator->trans('End date to this task is soon', array(), 'SDTaskBundle').':</i> ';
                $text .= "<br>";

                $text .= '<b>'.$translator->trans('Task', array(), 'SDTaskBundle').':</b> ';
                $text .= $taskUserRole->getTask()->getTitle();
                $text .= "<br>";

                $text .= '<b>'.$translator->trans('Task end date', array(), 'SDTaskBundle').':</b> ';
                $text .= $dateEnd->getEndDateTime()->format('d-m-Y H:i');
                $text .= "<br>";

                $url = $this->container->get('router')->generate(
                    'sd_task_homepage',
                    array(),
                    true
                ).'?id='.$taskUserRole->getId();
                $text .= '<b>'.$translator->trans('Link to task', array (), 'SDTaskBundle').':</b> ';
                $text .= '<a href="'.$url.'">'.$translator->trans('Link', array(), 'SDTaskBundle').'</a>';

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
