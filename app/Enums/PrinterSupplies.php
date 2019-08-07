<?php

declare(strict_types=1);

namespace App\Enums;

use ReflectionClass;
use ReflectionException;

class PrinterSupplies
{
    public const UNAVAILABLE = -1;
    public const UNKNOWN = -2;
    public const SOME_REMAINING = -3;

    /**
     * @return array|string[]
     */
    public static function values(): array
    {
        try {
            return (new ReflectionClass(__CLASS__))->getConstants();
        } catch (ReflectionException $exception) {
            return [];
        }
    }
}