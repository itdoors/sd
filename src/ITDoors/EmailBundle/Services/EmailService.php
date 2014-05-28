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
     *            '22222',
     *             array(
     *                 'users' => array(
     *                     'senj@mail.ru'  => 'Сергей'
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
     * @return boolean
     */
    public function send($from, $template, $to)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** Email $templateEmail */
        $templateEmail = $em->getRepository('ITDoorsEmailBundle:Email')
            ->findOneBy(array('alias' => $template));

        /** Swift_Mailer $mailer */
        $mailer = $this->container->get('mailer');

        $message = Swift_Message::newInstance()
            ->setSubject($templateEmail->getSubject())
            ->setFrom($from)
            ->setTo($to['users']);
        $body = $templateEmail->getText();
        if (array_key_exists('variables', $to)) {
            foreach ($to['variables'] as $key => $variable) {
                $body = str_replace($key, $variable, $body);
            }
        }
        $message->setBody($body, 'text/html');
        if (array_key_exists('files', $to)) {
            foreach ($to['files'] as $file) {
                $message->attach(Swift_Attachment::fromPath($file));
            }
        }

        return $mailer->send($message);
    }
}
