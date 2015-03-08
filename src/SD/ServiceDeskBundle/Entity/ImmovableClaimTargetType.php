<?php

namespace SD\ServiceDeskBundle\Entity;

final class ImmovableClaimTargetType extends \ITDoors\DBAL\EnumType
{
    const HOUSE = 'house';
    const VILLA = 'villa';
    const FLAT = 'flat';

    protected static $name = 'immovableClaimTargetType';

    protected static $values = array(
        self::HOUSE,
        self::VILLA,
        self::FLAT
    );
}