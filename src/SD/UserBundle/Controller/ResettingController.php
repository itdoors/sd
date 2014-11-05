<?php

namespace SD\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * UserController
 */
class ResettingController extends BaseController
{
    /**
     * sendEmailAction
     * 
     * @param Request $request
     * 
     * @return \SD\UserBundle\Controller\RedirectResponse
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');

        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->render('FOSUserBundle:Resetting:request.html.twig', array(
                'invalid_username' => $username
            ));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return $this->render('FOSUserBundle:Resetting:passwordAlreadyRequested.html.twig');
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $template = $this->container->getParameter('fos_user.resetting.email.template');
        $url = $this->generateUrl('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->renderView($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $renderedLines = explode("\n", trim($rendered));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $emailFrom = $this->container->getParameter('email.from');
        $nameFrom = $this->container->getParameter('name.from');
        $from = array($emailFrom => $nameFrom);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');

        $mailer = $this->container->get('mailer');
        $mailer->send($message);

        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        $cron = $this->get('it_doors_cron.service');
        $cron->addSendEmails();

        return new RedirectResponse(
            $this->generateUrl(
                'fos_user_resetting_check_email',
                array('email' => $this->getObfuscatedEmail($user))
            )
        );
    }
}
