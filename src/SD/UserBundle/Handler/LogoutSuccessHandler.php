<?php

namespace SD\UserBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SD\UserBundle\Entity\UserLoginRecord;

/**
 * LogoutSuccessHandler
 */
class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $em = $this->container->get('doctrine')->getManager();
        $session = $request->getSession();
        $loginRecord = $session->get('loginRecord');
        $userActivityRecord = $em->getRepository('SDUserBundle:UserActivityRecord')
                                    ->findOneBy(array('user' => $loginRecord->getUser()));

        if ($loginRecord) {
            $loginRecord->setLogedOut(new \DateTime(date('Y-m-d H:i:s')));

            $em->remove($userActivityRecord);
            $em->merge($loginRecord);
            $em->flush();
        }

        return new RedirectResponse($request->headers->get('referer'));
    }
}
