<?php

namespace SD\UserBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use SD\UserBundle\Entity\UserLoginRecord;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * LoginSuccessHandler
 */
class LoginSuccessHandler
{
    protected $router;
    protected $container;
    protected $security;

    /**
     * @param Container       $container
     * @param Router          $router
     * @param SecurityContext $security
     */
    public function __construct(Container $container, Router $router, SecurityContext $security)
    {
        $this->container = $container;
        $this->router = $router;
        $this->security = $security;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onAuthenticationSuccess(InteractiveLoginEvent $event)
    {
        $em = $this->container->get('doctrine')->getManager();
        $user = $event->getAuthenticationToken()->getUser();
        $request = $event->getRequest();
        $session = $request->getSession();
        $loginRecord = new UserLoginRecord();

        $loginRecord->setUser($user);
        $loginRecord->setLogedIn(new \DateTime(date('Y-m-d H:i:s')));
        $loginRecord->setClientIp($request->getClientIp());

        $em->persist($loginRecord);
        $em->flush();

        $session->set('loginRecord', $loginRecord);
    }
}
