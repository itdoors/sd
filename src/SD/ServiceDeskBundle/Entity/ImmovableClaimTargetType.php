<?php

namespace SD\ServiceDeskBundle\Entity;

final class ImmovableClaimTargetType extends \ITDoors\DBAL\EnumType
{
    const HOUSE = 'Дом';
    const VILLA = 'Дача';
    const FLAT = 'Квартира';

    protected static $name = 'immovableClaimTargetType';

    protected static $values = array(
        self::HOUSE,
        self::VILLA,
        self::FLAT
    );
}