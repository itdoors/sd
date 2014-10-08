<?php

namespace SD\UserBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * UserService
 */
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
     * @param array $options 
     * 
     * @return array
     */
    public function getTabs($options)
    {
        $translator = $this->container->get('translator');
        $tabs = array();
        $tabs['profile'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'profile',
            'url' => $this->container->get('router')->generate('sd_user_show_tabs'),
            'text' => $translator->trans('Profile', array(), 'SDUserBundle')
        );
        if ($options['settings']) {
            $tabs['settings'] = array(
                'blockupdate' => 'ajax-tab-holder',
                'tab' => 'settings',
                'url' => $this->container->get('router')->generate('sd_user_show_tabs'),
                'text' => $translator->trans('Settings profile', array(), 'SDUserBundle')
            );
        }
        $tabs['plan'] = array(
            'blockupdate' => 'ajax-tab-holder',
            'tab' => 'plan',
            'url' => $this->container->get('router')->generate('sd_user_show_tabs'),
            'text' => $translator->trans('Plan', array(), 'SDUserBundle')
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
    /**
     * Returns results for interval future invoice
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     * 
     * @return array
     */
    public function changeAvatar(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine')->getManager();
        $directory = $this->container->getParameter('project.dir');
        $directory .= '/web'.$this->container->getParameter('userprofiles.file.path');
        if (!is_dir($directory)) {
            $output->writeln("Directory not found {$directory}");

            return;
        }

        $users = $em->getRepository('SDUserBundle:User')->findAll();
        foreach ($users as $user) {
            $photo = $user->getPhoto();
            $output->writeln("User {$user->getId()} photo {$photo}");
            if (empty($photo)) {
                continue;
            }
            $newDir = $directory.$user->getId();
            $file = $newDir.'/image200_'.$photo;
            $format = null;
            if (is_file($file)) {
                $size = getimagesize($file);
                $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
                $user->setPhoto($user->getId().'.'.$format);
                $em->persist($user);
                copy($file, $newDir.'/'.$user->getId().'.'.$format);
            }
            $fileOrigin = $newDir.'/image872_'.$photo;
            if (is_file($fileOrigin) && $format) {
                $sizeOrigin = getimagesize($fileOrigin);
                $formatOrigin = strtolower(substr($sizeOrigin['mime'], strpos($sizeOrigin['mime'], '/')+1));
                if ($formatOrigin == $format) {
                    copy($fileOrigin, $newDir.'/original_'.$user->getId().'.'.$formatOrigin);
                } else {
                    copy($file, $newDir.'/original_'.$user->getId().'.'.$format);
                }
            }
        }
        $em->flush();
    }
}
