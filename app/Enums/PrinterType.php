<?php

declare(strict_types=1);

namespace App\Enums;

use ReflectionClass;
use ReflectionException;

class PrinterType
{
    public const COLOR = 'color printer';
    public const MONO = 'mono printer';

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