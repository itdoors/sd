<?php

namespace ITDoors\EmailBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use ITDoors\EmailBundle\Entity\Email;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

/**
 * Email Service class
 */
class EmailService
{

    /**
     * @var Container $container
     * 
     */
    protected $container;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * send
     * 
     * @param array  $from     sender
     * @param string $template alias
     * @param array  $to       to
     * 
     * $email = $this->get('it_doors_email.service');
     * $status = $email->send(
     *       array('senj1@mail.ru' => 'ITDoors'),
     *            'alias',
     *             array(
     *                 'users' => array(
     *                     'senj@mail.ru'
     *                 ),
     *                 'variables' => array(
     *                     '{$name}' => 'Имя пользователя',
     *                     '{$famely}' => 'фамилия пользователя'
     *                  ),
     *                  'files' => array(
     *                      'http://sait.ru/img.jpg',
     *                      'http://sait.ru/file.pdf'
     *                  )
     *          )
     *  );
     * 
     * @return $result
     */
    public function send($from, $template, $to)
    {
        if (!$from) {
            $emailFrom = $this->container->getParameter('email.from');
            $nameFrom = $this->container->getParameter('name.from');
            $from = array($emailFrom => $nameFrom);
        }
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** Email $templateEmail */
        $templateEmail = $em->getRepository('ITDoorsEmailBundle:Email')
            ->findOneBy(array('alias' => $template));

        if (!$templateEmail) {
            return false;
        }
        /** Swift_Mailer $mailer */
        $mailer = $this->container->get('mailer');

        if (!array_key_exists('users', $to)) {
            return false;
        }
        $result = array();
        foreach ($to['users'] as $email) {
            $message = Swift_Message::newInstance()
                ->setFrom($from)
                ->setTo($email);
            if (array_key_exists('variables', $to)) {
                $message->setBody($this->changeOfVariables($templateEmail->getText(), $to['variables']), 'text/html');
                $message->setSubject(
                    $this->changeOfVariables(
                        $templateEmail->getSubject(),
                        $to['variables']
                    ),
                    'text/html'
                );
            } else {
                $message->setBody($templateEmail->getSubject());
                $message->setBody($templateEmail->getText(), 'text/html');
            }
            if (array_key_exists('files', $to)) {
                $this->addFiles($message, $to['files']);
            }
            $mailer->send($message);
            $result[$email] = true;
        }

        return $result;
    }

    /**
     * changeOfVariables
     *
     * @param string $text
     * @param array  $variables
     * 
     * @return string
     */
    public function changeOfVariables($text, $variables)
    {
        foreach ($variables as $key => $variable) {
            $text = str_replace($key, $variable, $text);
        }

        return $text;
    }

    /**
     * addFiles
     *
     * @param Swift_Message $message
     * @param array         $files
     * 
     * @return Swift_Message
     */
    public function addFiles($message, $files)
    {
        foreach ($files as $file) {
            $message->attach(Swift_Attachment::fromPath($file));
        }

        return $message;
    }
}
