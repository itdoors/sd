<?php

namespace SD\ServiceDeskBundle\Entity;

final class StatusType extends \ITDoors\DBAL\EnumType
{
    const DONE = 'sta_sclose_smclose_cclose';
    const OPEN = 'sta_open';
    const SEND = 'sta_sappointed_smwait';
    const IN_PROGRESS = 'sta_sclose_smwait';
    const SUBMITTING = 'sta_sclose_smwait_cwait';
    const CREATING = 'sta_smeta_composу';
    const MATCHED = 'sta_smeta_conform';
    const CANCELED = 'sta_smeta_cancel';
    const ESTIMATING = 'sta_smet';
    const REJECTED = 'sta_sclose_smclose_cwait';
    protected static $name = 'statusType';

    protected static $values = array(
        self::DONE,
        self::OPEN,
        self::SEND,
        self::IN_PROGRESS,
        self::SUBMITTING,
        self::CREATING,
        self::MATCHED,
        self::CANCELED,
        self::ESTIMATING,
        self::REJECTED
    );
}