<?php

namespace App\Printing;

use Exception;

class Checker
{
    /**
     * @var string IP address
     */
    protected $ip;

    /**
     * The max number of microseconds for SNMP call
     * Default value is set to 0,1 second
     *
     * @var int microseconds
     */
    protected $maxTimeout = 0.1 * 1000 * 1000;


    /**
     * Printer types
     */
    const PRINTER_TYPE_MONO  = 'mono printer';
    const PRINTER_TYPE_COLOR = 'color printer';

    /**
     * Printer colors
     */
    const CARTRIDGE_COLOR_CYAN    = 'cyan';
    const CARTRIDGE_COLOR_MAGENTA = 'magenta';
    const CARTRIDGE_COLOR_YELLOW  = 'yellow';
    const CARTRIDGE_COLOR_BLACK   = 'black';

    /**
     * SNMP MARKER_SUPPLIES possible results
     */
    const MARKER_SUPPLIES_UNAVAILABLE    = -1;
    const MARKER_SUPPLIES_UNKNOWN        = -2;
    const MARKER_SUPPLIES_SOME_REMAINING = -3; // means that there is some remaining but unknown how much

    /**
     * SNMP printer object ids
     */
    const SNMP_PRINTER_FACTORY_ID                     = '.1.3.6.1.2.1.1.1.0';
    const SNMP_PRINTER_RUNNING_TIME                   = '.1.3.6.1.2.1.1.3.0';
    const SNMP_PRINTER_SERIAL_NUMBER                  = '.1.3.6.1.2.1.43.5.1.1.17.1';
    const SNMP_PRINTER_VENDOR_NAME                    = '.1.3.6.1.2.1.43.9.2.1.8.1.1';
    const SNMP_NUMBER_OF_PRINTED_PAPERS               = '.1.3.6.1.2.1.43.10.2.1.4.1.1';
    const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOTS     = '.1.3.6.1.2.1.43.11.1.1.8.1';
    const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_1    = '.1.3.6.1.2.1.43.11.1.1.8.1.1';
    const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_2    = '.1.3.6.1.2.1.43.11.1.1.8.1.2';
    const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_3    = '.1.3.6.1.2.1.43.11.1.1.8.1.3';
    const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_4    = '.1.3.6.1.2.1.43.11.1.1.8.1.4';
    const SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_5    = '.1.3.6.1.2.1.43.11.1.1.8.1.5';
    const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOTS  = '.1.3.6.1.2.1.43.11.1.1.9.1';
    const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_1 = '.1.3.6.1.2.1.43.11.1.1.9.1.1';
    const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_2 = '.1.3.6.1.2.1.43.11.1.1.9.1.2';
    const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_3 = '.1.3.6.1.2.1.43.11.1.1.9.1.3';
    const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_4 = '.1.3.6.1.2.1.43.11.1.1.9.1.4';
    const SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_5 = '.1.3.6.1.2.1.43.11.1.1.9.1.5';
    const SNMP_SUB_UNIT_TYPE_SLOTS                    = '.1.3.6.1.2.1.43.11.1.1.6.1';
    const SNMP_SUB_UNIT_TYPE_SLOT_1                   = '.1.3.6.1.2.1.43.11.1.1.6.1.1';
    const SNMP_SUB_UNIT_TYPE_SLOT_2                   = '.1.3.6.1.2.1.43.11.1.1.6.1.2';
    const SNMP_SUB_UNIT_TYPE_SLOT_3                   = '.1.3.6.1.2.1.43.11.1.1.6.1.3';
    const SNMP_SUB_UNIT_TYPE_SLOT_4                   = '.1.3.6.1.2.1.43.11.1.1.6.1.4';
    const SNMP_CARTRIDGE_COLOR_SLOT_1                 = '.1.3.6.1.2.1.43.12.1.1.4.1.1';
    const SNMP_CARTRIDGE_COLOR_SLOT_2                 = '.1.3.6.1.2.1.43.12.1.1.4.1.2';
    const SNMP_CARTRIDGE_COLOR_SLOT_3                 = '.1.3.6.1.2.1.43.12.1.1.4.1.3';
    const SNMP_CARTRIDGE_COLOR_SLOT_4                 = '.1.3.6.1.2.1.43.12.1.1.4.1.4';

    /**
     * Contructor can set the ip address
     * and the maximum timeout in microseconds for SNMP call
     *
     * @param string $ip IP address
     * @param int $timeout microseconds
     * @throws Exception if PHP SNMP extension is not loaded
     */
    public function __construct($ip = null, $timeout = null)
    {
        if (!extension_loaded('snmp')) {
            throw new Exception('SNMP extension is not loaded');
        }

        if ($ip !== null) {
            $this->setIPAddress($ip);
        }

        if ($timeout !== null) {
            $this->setMaxTimeout($timeout);
        }

        snmp_set_valueretrieval(SNMP_VALUE_OBJECT | SNMP_VALUE_PLAIN);
    }

    /**
     * Function returns IP address
     *
     * @return string IP address
     * @throws Exception if IP address is not set
     */
    public function __toString()
    {
        /**
         * Check if IP address is set
         */
        if ($this->ip === null) {
            throw new Exception('IP address was not set.');
        }

        return $this->ip;
    }

    /**
     * Function sets IP address
     *
     * @param string $ip IP address
     * @throws Exception if passed IP address is not in string format
     */
    public function setIPAddress($ip)
    {
        /**
         * Check if IP address is string
         */
        if (!is_string($ip)) {
            throw new Exception('Passed IP address is not string.');
        }

        $this->ip = $ip;
    }

    /**
     * Function gets IP address
     *
     * @return string
     */
    public function getIPAddress()
    {
        return $this->ip;
    }

    /**
     * Function sets maximum timeout in microseconds for SNMP calls
     *
     * @param int $microseconds
     * @throws Exception if passed timeout in microseconds is not in integer format
     */
    public function setMaxTimeout($microseconds)
    {
        /**
         * Check if timeout is integer
         */
        if (!is_int($microseconds)) {
            throw new Exception('Passed timeout is not int.');
        }

        $this->maxTimeout = $microseconds;
    }

    /**
     * Function gets maxTimeout
     *
     * @return int Microseconds
     */
    public function getMaxTimeout()
    {
        return $this->maxTimeout;
    }

    /**
     * Function gets result of SNMP object id,
     * or returns false if call failed
     *
     * @param string $snmpObjectId
     * @return \stdClass
     * @throws Exception if IP address is not set
     * @throws Exception if $snmpObjectId is not in string format
     */
    public function get($snmpObjectId)
    {
        /**
         * Check if IP address is set
         */
        if ($this->ip === null) {
            throw new Exception('IP address was not set.');
        }

        /**
         * Check if SNMP object ID is in string format
         */
        if (!is_string($snmpObjectId)) {
            throw new Exception('SNMP Object ID is not string.');
        }

        return @snmpget($this->ip, 'public', $snmpObjectId, $this->maxTimeout);
    }

    /**
     * Function walks through SNMP object id and returns result in array,
     * or returns false of call failed
     *
     * @param string $snmpObjectId
     * @return array
     * @throws Exception if IP address is not set
     * @throws Exception if $snmpObjectId is not in string format
     */
    public function walk($snmpObjectId)
    {
        /**
         * Check if IP address is set
         */
        if ($this->ip === null) {
            throw new Exception('IP address was not set.');
        }

        /**
         * Check if SNMP object ID is in string format
         */
        if (!is_string($snmpObjectId)) {
            throw new Exception('SNMP Object ID is not string.');
        }

        return @snmpwalk($this->ip, 'public', $snmpObjectId, $this->maxTimeout);
    }

    /**
     * Function gets result of SNMP object id with deleted quotation marks,
     * or returns false if call failed
     *
     * @param string $snmpObjectId
     * @return mixed
     * @throws Exception
     */
    public function getSNMPString($snmpObjectId)
    {
        $result = $this->get($snmpObjectId);

        if ($result === false) {
            return null;
        }

        switch ($result->type) {
            case SNMP_OCTET_STR:
                return $result->value;
            case SNMP_TIMETICKS:
                return (int) ($result->value / 100);
            case SNMP_INTEGER:
            case SNMP_COUNTER:
            case SNMP_COUNTER64:
            case SNMP_UNSIGNED:
                return (int) $result->value;
            default:
                throw new Exception('Unrecognised snmp type: ' . $result->type);
        }
    }

    /**
     * Function gets and return what type of printer we are working with,
     * or returns false if error occurred
     *
     * @return string Type of printer (PRINTER_TYPE_MONO|PRINTER_TYPE_COLOR)
     * @throws Exception
     */
    public function getTypeOfPrinter()
    {
        $colorCartridgeSlot1 = $this->getSNMPString(self::SNMP_CARTRIDGE_COLOR_SLOT_1);

        if ($colorCartridgeSlot1 !== false) {
            if (strtolower($colorCartridgeSlot1) === self::CARTRIDGE_COLOR_CYAN) {
                /**
                 * We found CYAN color catridge in slot1 so it is color printer
                 */
                return self::PRINTER_TYPE_COLOR;
            } else {
                /**
                 * else it is mono printer
                 */
                return self::PRINTER_TYPE_MONO;
            }
        }

        return false;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getUptime()
    {
        return $this->getSNMPString(self::SNMP_PRINTER_RUNNING_TIME);
    }

    /**
     * Function returns true if it is color printer
     *
     * @return boolean
     * @throws Exception
     */
    public function isColorPrinter()
    {
        $type = $this->getTypeOfPrinter();
        if ($type !== false) {
            return ($type === self::PRINTER_TYPE_COLOR)
                ? true
                : false;
        } else {
            return false;
        }
    }

    /**
     * Function returns true if it is color printer
     *
     * @return boolean
     * @throws Exception
     */
    public function isMonoPrinter()
    {
        $type = $this->getTypeOfPrinter();
        if ($type !== false) {
            return ($type === self::PRINTER_TYPE_MONO)
                ? true
                : false;
        } else {
            return false;
        }
    }

    /**
     * Function gets factory id (name) of the printer,
     * or returns false if call failed
     *
     * @return string|boolean
     * @throws Exception
     */
    public function getFactoryId()
    {
        return $this->getSNMPString(self::SNMP_PRINTER_FACTORY_ID);
    }

    /**
     * Function gets vendor name of printer
     *
     * @return string|boolean
     * @throws Exception
     */
    public function getVendorName()
    {
        return $this->getSNMPString(self::SNMP_PRINTER_VENDOR_NAME);
    }

    /**
     * Function gets serial number of printer
     *
     * @return string|boolean
     * @throws Exception
     */
    public function getSerialNumber()
    {
        return $this->getSNMPString(self::SNMP_PRINTER_SERIAL_NUMBER);
    }

    /**
     * Function gets a count of printed papers,
     * or returns false if call failed
     *
     * @return int|boolean
     * @throws Exception
     */
    public function getNumberOfPrintedPapers()
    {
        snmp_set_quick_print(true);

        $numberOfPrintedPapers = $this->get(self::SNMP_NUMBER_OF_PRINTED_PAPERS);

        snmp_set_quick_print(false);

        return ($numberOfPrintedPapers !== false)
            ? (int) $numberOfPrintedPapers
            : false;
    }

    /**
     * Function gets description about black catridge of the printer,
     * or returns false if call failed
     *
     * @return string|boolean
     * @throws Exception
     */
    public function getBlackCatridgeType()
    {
        if ($this->isColorPrinter()) {
            return $this->getSNMPString(self::SNMP_SUB_UNIT_TYPE_SLOT_4);
        } elseif ($this->isMonoPrinter()) {
            return $this->getSNMPString(self::SNMP_SUB_UNIT_TYPE_SLOT_1);
        } else {
            return false;
        }
    }

    /**
     * Function gets description about cyan catridge of the printer,
     * or returns false if call failed
     *
     * @return string|boolean
     * @throws Exception
     */
    public function getCyanCatridgeType()
    {
        if ($this->isColorPrinter()) {
            return $this->getSNMPString(self::SNMP_SUB_UNIT_TYPE_SLOT_1);
        } else {
            return false;
        }
    }

    /**
     * Function gets description about magenta catridge of the printer,
     * or returns false if call failed
     *
     * @return string|boolean
     * @throws Exception
     */
    public function getMagentaCatridgeType()
    {
        if ($this->isColorPrinter()) {
            return $this->getSNMPString(self::SNMP_SUB_UNIT_TYPE_SLOT_2);
        } else {
            return false;
        }
    }

    /**
     * Function gets description about yellow catridge of the printer,
     * or returns false if call failed
     *
     * @return string|boolean
     * @throws Exception
     */
    public function getYellowCatridgeType()
    {
        if ($this->isColorPrinter()) {
            return $this->getSNMPString(self::SNMP_SUB_UNIT_TYPE_SLOT_3);
        } else {
            return false;
        }
    }

    /**
     * Function gets sub-unit percentage level of the printer,
     * or
     * -1 : MARKER_SUPPLIES_UNAVAILABLE Level is unavailable
     * -2 : MARKER_SUPPLIES_UNKNOWN Level is unknown
     * -3 : MARKER_SUPPLIES_SOME_REMAINING Information about level is only that there is some remaining, but we don't know how much
     *
     * or returns false if call failed
     *
     * @param string $maxValueSNMPSlot SNMP object id
     * @param string $actualValueSNMPSlot SNMP object id
     * @return int|float|boolean
     * @throws Exception
     */
    protected function getSubUnitPercentageLevel($maxValueSNMPSlot, $actualValueSNMPSlot)
    {
        $max = $this->get($maxValueSNMPSlot);
        $actual = $this->get($actualValueSNMPSlot);

        if ($max === false || $actual === false) {
            return false;
        }

        if ((int) $actual <= 0) {
            /**
             * Actual level of drum is unavailable, unknown or some unknown remaining
             */
            return (int) $actual;
        } else {
            /**
             * Counting result in percent format
             */
            return ($actual / ($max / 100));
        }
    }

    /**
     * Function gets actual level of black toner (in percents)
     * or returns false if call failed
     *
     * @see getSubUnitPercentageLevel
     * @return int|float|boolean
     * @throws Exception
     */
    public function getBlackTonerLevel()
    {
        if ($this->isColorPrinter()) {
            return $this->getSubUnitPercentageLevel(
                self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_4,
                self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_4
            );
        } elseif ($this->isMonoPrinter()) {
            return $this->getSubUnitPercentageLevel(
                self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_1,
                self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_1
            );
        } else {
            return false;
        }
    }

    /**
     * Function gets actual level of cyan toner (in percents)
     * or returns false if call failed
     *
     * @see getSubUnitPercentageLevel
     * @return int|float|boolean
     * @throws Exception
     */
    public function getCyanTonerLevel()
    {
        if ($this->isColorPrinter()) {
            return $this->getSubUnitPercentageLevel(
                self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_1,
                self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_1
            );
        } else {
            return false;
        }
    }

    /**
     * Function gets actual level of magenta toner (in percents)
     * or returns false if call failed
     *
     * @see getSubUnitPercentageLevel
     * @return int|float|boolean
     * @throws Exception
     */
    public function getMagentaTonerLevel()
    {
        if ($this->isColorPrinter()) {
            return $this->getSubUnitPercentageLevel(
                self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_2,
                self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_2
            );
        } else {
            return false;
        }
    }

    /**
     * Function gets actual level of yellow toner (in percents)
     * or returns false if call failed
     *
     * @see getSubUnitPercentageLevel
     * @return int|float|boolean
     * @throws Exception
     */
    public function getYellowTonerLevel()
    {
        if ($this->isColorPrinter()) {
            return $this->getSubUnitPercentageLevel(
                self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_3,
                self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_3
            );
        } else {
            return false;
        }
    }

    /**
     * Function gets drum level of the printer (in percents)
     * or returns false if call failed
     *
     * @see getSubUnitPercentageLevel
     * @return int|float|boolean
     * @throws Exception
     */
    public function getDrumLevel()
    {
        if ($this->isColorPrinter()) {
            return $this->getSubUnitPercentageLevel(
                self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_5,
                self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_5
            );
        } elseif ($this->isMonoPrinter()) {
            return $this->getSubUnitPercentageLevel(
                self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_2,
                self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_2
            );
        } else {
            return false;
        }
    }

    /**
     * Function walks through SNMP object ids of Sub-Units and returns results of them all in array
     * with calculated percentage level
     *
     * @return array
     * @throws Exception
     */
    public function getAllSubUnitData()
    {
        $names        = $this->walk(self::SNMP_SUB_UNIT_TYPE_SLOTS);
        $maxValues    = $this->walk(self::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOTS);
        $actualValues = $this->walk(self::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOTS);

        $resultData = [];
        for ($i = 0; $i < sizeOf($names); $i++) {
            $resultData[] = [
                'name'            => str_replace('"', '', $names[$i]),
                'maxValue'        => $maxValues[$i],
                'actualValue'     => $actualValues[$i],
                'percentageLevel' => ((int)$actualValues[$i] >= 0)
                    ? ($actualValues[$i] / ($maxValues[$i] / 100))
                    : null
            ];
        }

        return $resultData;
    }


}