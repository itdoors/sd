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
        $pdo = $this->container->get('session.handler.pdo');
        $session = $request->getSession();
        if ($session->get('loginRecord')) {
            $user = $session->get('loginRecord')->getUser();
            $userActivityRecords = $em->getRepository('SDUserBundle:UserActivityRecord')
                                        ->findBy(array('user' => $user));
            if (count($userActivityRecords) > 0) {
                $userLoginRecordsRepository = $em->getRepository('SDUserBundle:UserLoginRecord');
                $userLoginRecords = $userLoginRecordsRepository->findBy(array(
                                'user' => $user,
                                'logedOut' => null
                ));
                foreach ($userLoginRecords as $userLoginRecord) {
                    $userLoginRecord->setLogedOut(new \DateTime("now"));
                    $userLoginRecord->setCause('self_logout');
                    $em->merge($userLoginRecord);
                }

                $em->remove($userActivityRecords[0]);
                $em->flush();
                $pdo->destroy($session->getId());
            }
        }

        return new RedirectResponse($request->headers->get('referer'));
    }
}
