<?php
namespace ITDoors\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumType extends Type
{
    protected static $name;
    protected static $values = [];

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        static::$values = array_map(function($val) { return "'".$val."'"; }, static::$values);

        return "ENUM(".implode(", ", static::$values).") COMMENT '(DC2Type:".static::$name.")'";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, static::$values)) {
            throw new \InvalidArgumentException("Invalid '".static::$name."' value.");
        }
        return $value;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public static function values()
    {
        $values = [];
        foreach (static::$values as $value) {
            $values[''] = '';
            $values[$value] = $value;
        }
        return $values;
    }
}
