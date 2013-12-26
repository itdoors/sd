<?php

namespace SD\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    protected $baseTemplate = 'User';

    /**
     * Executes index action
     */
    public function indexAction()
    {
        $users = $this->get('sd_user.repository')->getOnlyStaff()
            ->getQuery()
            ->getResult();

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':index.html.twig', array(
                'items' => $users,
                'baseTemplate' => $this->baseTemplate
            ));
    }

    /**
     * Execute show action
     */
    public function showAction($id)
    {
        $user = $this->get('sd_user.repository')->find($id);

        if (!$user)
        {
            return $this->render($this->generateUrl('sd_user_index'));
        }

        return $this->render('SDUserBundle:' . $this->baseTemplate . ':show.html.twig', array(
                'item' => $user,
                'baseTemplate' => $this->baseTemplate
            ));
    }
}
