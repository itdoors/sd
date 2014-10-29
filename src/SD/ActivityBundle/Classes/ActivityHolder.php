<?php

namespace SD\ActivityBundle\Classes;

/**
 * ActivityHolder class
 */
class ActivityHolder
{

    public $message;
    public $date;

    /**
     * @param string              $message
     * @param \Datetime|string    $date
     */
    public function __construct($message, $date)
    {
        $this->message = $message;
        if ($date instanceof \DateTime) {
            $this->date = $date->format('d-m-Y H:i');
        } else {
            $this->date = $date;
        }
    }
}
