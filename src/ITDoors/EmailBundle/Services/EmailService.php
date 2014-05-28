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
     * 
     * @param array  $from
     * @param string $template
     * @param array  $to 
     */
    public function send($from, $template, $to)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
       
        /** Email $templateHTML */
        $template_email = $em->getRepository('ITDoorsEmailBundle:Email')
            ->findOneBy(array('alias' => $template));
        
        /** Swift_Mailer $mailer */
        $mailer = $this->container->get('mailer');

        $message = Swift_Message::newInstance()
            ->setSubject($template_email->getSubject())
            ->setFrom($from)
            ->setTo($to['users']);
        $body = $template_email->getText();
        if(array_key_exists('variables', $to)){
            foreach ($to['variables'] as $key => $variable){
                $body = str_replace($key, $variable, $body);
            }
        }
        $message->setBody($body, 'text/html');
        if(array_key_exists('files', $to)){
            foreach ($to['files'] as $file){
                $message->attach(Swift_Attachment::fromPath($file));
            }
        }
        
        return $mailer->send($message);
        
 
    }
}
