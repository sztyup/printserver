<?php

declare(strict_types=1);

namespace App\Enums;

use ReflectionClass;
use ReflectionException;

final class PrinterObjectAttribute
{
    public const AUTH_INFO_REQUIRED = 'auth-info-required';
    public const JOB_SHEETS_DEFAULT = 'job-sheets-default';
    public const DEVICE_URI = 'device-uri';
    public const PORT_MONITOR = 'port-monitor';
    public const PPD_NAME = 'ppd-name';
    public const PRINTER_IS_ACCEPTING_JOBS = 'printer-is-accepting-jobs';
    public const PRINTER_INFO = 'printer-info';
    public const PRINTER_LOCATION = 'printer-location';
    public const PRINTER_MORE_INFO = 'printer-more-info';
    public const PRINTER_STATE = 'printer-state';
    public const PRINTER_STATE_MESSAGE = 'printer-state-message';
    public const REQUESTING_USER_NAME_ALLOWED = 'requesting-user-name-allowed';
    public const REQUESTING_USER_NAME_DENIED = 'requesting-user-name-denied';

    /**
     * @return array|string[]
     * @throws ReflectionException
     */
    public static function getAttributes(): array
    {
        return (new ReflectionClass(__CLASS__))->getConstants();
    }
}