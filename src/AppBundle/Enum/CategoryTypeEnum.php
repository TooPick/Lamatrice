<?php

namespace AppBundle\Enum;

abstract class CategoryTypeEnum
{
    const TYPE_STATIONERY = "stationery";
    const TYPE_PLASTIC = "plastic";

    /** @var array user friendly named type */
    protected static $typeName = [
        self::TYPE_STATIONERY => 'Papeterie',
        self::TYPE_PLASTIC => 'Plastique',
    ];

    /**
     * @param  string $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Catégorie inconue ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_STATIONERY,
            self::TYPE_PLASTIC,
        ];
    }

}
