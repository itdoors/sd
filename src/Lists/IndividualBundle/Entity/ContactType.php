<?php

namespace Lists\IndividualBundle\Entity;

/**
 * ContactType
 */
final class ContactType extends \ITDoors\DBAL\EnumType
{
    const TEL = 'tel';
    const EMAIL = 'email';
    const ADDRESS = 'address';
    protected static $name = 'contactType';

    protected static $values = array(
                    self::TEL,
                    self::EMAIL,
                    self::ADDRESS
    );
}
