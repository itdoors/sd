<?php

namespace SD\ServiceDeskBundle\Entity;

final class OrganizationType extends \ITDoors\DBAL\EnumType
{
    const NETWORK = 'network';
    const LOCAL = 'local';
    const ONCE = 'once';

    protected static $name = 'organizationType';

    protected static $values = array(
        self::NETWORK,
        self::LOCAL,
        self::ONCE
    );
}