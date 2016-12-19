<?php

namespace AppBundle\Enum;

abstract class StatusTypeEnum
{
    const TYPE_UNTREATED = "untreated";
    const TYPE_BEING_PROCESSED = "being_processed";
    const TYPE_PROCESSED = "processed";

    /** @var array user friendly named type */
    protected static $typeName = [
        self::TYPE_UNTREATED => 'Non traité',
        self::TYPE_BEING_PROCESSED => 'En cours de traitement',
        self::TYPE_PROCESSED => 'Traité',
    ];

    /**
     * @param  string $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName)
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Status inconnu ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_UNTREATED,
            self::TYPE_BEING_PROCESSED,
            self::TYPE_PROCESSED,
        ];
    }

}
