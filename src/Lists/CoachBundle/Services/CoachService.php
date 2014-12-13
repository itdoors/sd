<?php

namespace Lists\CoachBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

/**
 * CoachService class
 */
class CoachService
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
    public function __construct (Container $container)
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
        $tabs['reports'] = array(
                        'blockupdate' => 'ajax-tab-holder',
                        'tab' => 'reports',
                        'url' => $this->container->get('router')->generate('lists_coach_report_showtabs'),
                        'text' => $translator->trans('Reports', array(), 'ListsCoachBundle')
        );
        if ($options['coachAdmin']) {
            $tabs['settings'] = array(
                            'blockupdate' => 'ajax-tab-holder',
                            'tab' => 'settings',
                            'actionTopics' => $this->getActionTopics(),
                            'actionTypes' => $this->getActionTypes(),
                            'url' => $this->container->get('router')->generate('lists_coach_report_showtabs'),
                            'text' => $translator->trans('Settings', array(), 'ListsCoachBundle')
            );
            $tabs['coaches'] = array(
                            'blockupdate' => 'ajax-tab-holder',
                            'tab' => 'coaches',
                            'url' => $this->container->get('router')->generate('lists_coach_report_showtabs'),
                            'text' => $translator->trans('Coaches', array(), 'ListsCoachBundle')
            );
        }

        return $tabs;
    }

    /**
     * Returns all possible action topics
     *
     * @return array
     */
    public function getActionTopics()
    {
        $actionTopics = $this
            ->container->get('doctrine')
            ->getRepository('ListsCoachBundle:ActionTopic')
            ->findAll();

        return $actionTopics;
    }

    /**
     * Returns all possible action types
     *
     * @return array
     */
    public function getActionTypes()
    {
        $actionTypes = $this
            ->container->get('doctrine')
            ->getRepository('ListsCoachBundle:ActionType')
            ->findAll();

        return $actionTypes;
    }

    /**
     * Checks for being coach
     * 
     * @param int $userId
     *
     * @return bool
     */
    public function isCoach($userId)
    {
        $user = $this
            ->container->get('sd_user.repository')
            ->find($userId);

        return $user->hasRole('ROLE_COACH');
    }
}
