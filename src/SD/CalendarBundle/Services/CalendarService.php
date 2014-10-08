<?php

namespace SD\CalendarBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Translation\Translator;

/**
 * Calendar Service class
 */
class CalendarService
{
    const EVENT_TYPE_CHOICE_LAST = 'last';
    const EVENT_TYPE_CHOICE_ALL = 'all';

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * @var Translator $translator
     */
    protected $translator;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->translator = $this->container->get('translator');
    }

    /**
     * Returns dashboard filter choices of events for calendar
     *
     * @return mixed[]
     */
    public function getDashboardEventChoices()
    {
        return array(
            self::EVENT_TYPE_CHOICE_LAST => $this->translator->trans('Last Events', array(), 'SDCalendarBundle'),
            self::EVENT_TYPE_CHOICE_ALL => $this->translator->trans('All Events', array(), 'SDCalendarBundle')
        );
    }
}
