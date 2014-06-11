<?php

namespace SD\UserBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Response;

class UserService
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
     * Returns results for interval future invoice
     *
     * @var Container
     * 
     * @return array
     */
    public function getTabs()
    {
        $translator = $this->container->get('translator');
        $tabs = array();
        $tabs['profile'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'profile',
            'url' => $this->container->get('router')->generate('sd_user_show_tabs'),
            'text' => $translator->trans('Profile',array(),'SDUserBundle')
        );
        $tabs['settings'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'settings',
            'url' => $this->container->get('router')->generate('sd_user_show_tabs'),
            'text' => $translator->trans('Settings profile',array(),'SDUserBundle')
        );
        $tabs['plan'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'plan',
            'url' => $this->container->get('router')->generate('sd_user_show_tabs'),
            'text' => $translator->trans('Plan',array(),'SDUserBundle')
        );
//        if($pass){
//          $tabs['pass'] = array(
//            'blockupdate' => 'ajax-tab-holder',
//            'tab' => 'pass',
//            'url' => $this->container->get('router')->generate('sd_user_show_pass'),
//            'text' => $translator->trans('Change Password')
//        );  
//        }

        return $tabs;
    }

}