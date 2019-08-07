<?php

declare(strict_types=1);

namespace App\Enums;

use ReflectionClass;
use ReflectionException;

class SNMPObjectId
{
    public const SNMP_PRINTER_FACTORY_ID                     = '.1.3.6.1.2.1.1.1.0';
    public const SNMP_PRINTER_RUNNING_TIME                   = '.1.3.6.1.2.1.1.3.0';
    public const SNMP_PRINTER_SERIAL_NUMBER                  = '.1.3.6.1.2.1.43.5.1.1.17.1';
    public const SNMP_PRINTER_VENDOR_NAME                    = '.1.3.6.1.2.1.43.9.2.1.8.1.1';
    public const SNMP_NUMBER_OF_PRINTED_PAPERS               = '.1.3.6.1.2.1.43.10.2.1.4.1.1';
    public const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOTS     = '.1.3.6.1.2.1.43.11.1.1.8.1';
    public const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_1    = '.1.3.6.1.2.1.43.11.1.1.8.1.1';
    public const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_2    = '.1.3.6.1.2.1.43.11.1.1.8.1.2';
    public const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_3    = '.1.3.6.1.2.1.43.11.1.1.8.1.3';
    public const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_4    = '.1.3.6.1.2.1.43.11.1.1.8.1.4';
    public const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_5    = '.1.3.6.1.2.1.43.11.1.1.8.1.5';
    public const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOTS  = '.1.3.6.1.2.1.43.11.1.1.9.1';
    public const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_1 = '.1.3.6.1.2.1.43.11.1.1.9.1.1';
    public const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_2 = '.1.3.6.1.2.1.43.11.1.1.9.1.2';
    public const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_3 = '.1.3.6.1.2.1.43.11.1.1.9.1.3';
    public const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_4 = '.1.3.6.1.2.1.43.11.1.1.9.1.4';
    public const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_5 = '.1.3.6.1.2.1.43.11.1.1.9.1.5';
    public const SNMP_SUB_UNIT_TYPE_SLOTS                    = '.1.3.6.1.2.1.43.11.1.1.6.1';
    public const SNMP_SUB_UNIT_TYPE_SLOT_1                   = '.1.3.6.1.2.1.43.11.1.1.6.1.1';
    public const SNMP_SUB_UNIT_TYPE_SLOT_2                   = '.1.3.6.1.2.1.43.11.1.1.6.1.2';
    public const SNMP_SUB_UNIT_TYPE_SLOT_3                   = '.1.3.6.1.2.1.43.11.1.1.6.1.3';
    public const SNMP_SUB_UNIT_TYPE_SLOT_4                   = '.1.3.6.1.2.1.43.11.1.1.6.1.4';
    public const SNMP_CARTRIDGE_COLOR_SLOT_1                 = '.1.3.6.1.2.1.43.12.1.1.4.1.1';
    public const SNMP_CARTRIDGE_COLOR_SLOT_2                 = '.1.3.6.1.2.1.43.12.1.1.4.1.2';
    public const SNMP_CARTRIDGE_COLOR_SLOT_3                 = '.1.3.6.1.2.1.43.12.1.1.4.1.3';
    public const SNMP_CARTRIDGE_COLOR_SLOT_4                 = '.1.3.6.1.2.1.43.12.1.1.4.1.4';

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