<?php

namespace Lists\HandlingBundle\Services;

/**
 * HandlingMessageService class
 */
class HandlingMessageService
{
    const SLUG_CALL = 'call';
    const SLUG_PRESENTATION = 'presentation';
    const SLUG_AUDIT = 'audit';
    const SLUG_SPACIFICATION = 'specification';
    const SLUG_TENDER = 'tender';

    /**
     * @var string[] $eventColors
     */
    public static $eventColors = array(
        'grey' => 'sd-event-grey',
        'red' => 'sd-event-red',
        'yellow' => 'sd-event-yellow',
        'blue' => 'sd-event-blue',
        'green' => 'sd-event-green'
    );

    /**
     * @var string[]
     */
    public static $reportSlugs = array(
        self::SLUG_CALL,
        self::SLUG_PRESENTATION,
        self::SLUG_AUDIT,
        self::SLUG_SPACIFICATION,
        self::SLUG_TENDER
    );

    /**
     * @return mixed[]
     */
    public function getReportSlugs()
    {
        return self::$reportSlugs;
    }
}
