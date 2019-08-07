<?php

declare(strict_types=1);

namespace App\Enums;

use ReflectionClass;
use ReflectionException;

class PrinterColor
{
    public const CYAN = 'cyan';
    public const MAGENTA = 'magenta';
    public const YELLOW = 'yellow';
    public const BLACK = 'black';

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