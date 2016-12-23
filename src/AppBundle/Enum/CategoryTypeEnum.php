<?php

namespace AppBundle\Enum;

/**
 * Représente les catégories de produits disponibles dans le magasin.
 *
 * @package AppBundle\Enum
 */
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
     * Renvoi le nom d'une catégorie à partir de sa clé.
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
     * Renvoi la liste des catégories disponibles.
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
