<?php

declare(strict_types=1);

namespace App\Mediator;

use App\Enums\PrinterColor;
use App\Enums\PrinterType;
use App\Enums\SNMPObjectId;
use App\Network\Model\SNMPResponse;
use RuntimeException;
use Webmozart\Assert\Assert;

class PrinterMediator implements PrinterMediatorInterface
{
    /** @var int */
    private const TIMEOUT = 1000000;

    /**
     * PrinterMediator constructor.
     */
    public function __construct()
    {
        if (!extension_loaded('snmp')) {
            throw new RuntimeException('SNMP extension is not loaded');
        }

        snmp_set_valueretrieval(SNMP_VALUE_OBJECT | SNMP_VALUE_PLAIN);
    }

    /**
     * @param string $host
     * @param string $objectId
     * @return SNMPResponse
     */
    protected function get(string $host, string $objectId): SNMPResponse
    {
        $snmpget = @snmpget($host, 'public', $objectId, self::TIMEOUT);
        dump($snmpget);
        Assert::notNull($snmpget);
        Assert::notSame($snmpget, false);
        Assert::integer($snmpget->type);
        Assert::notNull($snmpget->value);

        return new SNMPResponse($snmpget->type, $snmpget->value);
    }

    /**
     * @param string $host
     * @param string $objectId
     * @return int
     */
    protected function getInt(string $host, string $objectId): int
    {
        $response = $this->get($host, $objectId);
        Assert::integer($response->getValue());

        if ($response->getType() === SNMP_TIMETICKS) {
            return (int) (intval($response->getValue()) / 100);
        }

        Assert::oneOf($response->getType(), [
            SNMP_INTEGER,
            SNMP_COUNTER,
            SNMP_COUNTER64,
            SNMP_UNSIGNED
        ]);

        return intval($response->getValue());
    }

    /**
     * @param string $host
     * @param string $objectId
     * @return string
     */
    protected function getString(string $host, string $objectId): string
    {
        $response = $this->get($host, $objectId);
        Assert::eq($response->getType(), SNMP_OCTET_STR);

        return '' . $response->getValue();
    }

    /**
     * @param string $host
     * @param string $objectId
     * @return array
     */
    protected function walk(string $host, string $objectId): array
    {
        $response = @snmpwalk($host, 'public', $objectId, self::TIMEOUT);
        Assert::isArray($response);

        return $response;
    }

    /**
     * {@inheritDoc
     */
    public function getType(string $host): string
    {
        $slot = $this->getString($host, SNMPObjectId::SNMP_CARTRIDGE_COLOR_SLOT_1);
        $slot = strtolower($slot);

        if ($slot === PrinterColor::CYAN) {
            return PrinterType::COLOR;
        }

        return PrinterType::MONO;
    }

    /**
     * {@inheritDoc
     */
    public function getUptime(string $host): int
    {
        return $this->getInt($host, SNMPObjectId::SNMP_PRINTER_RUNNING_TIME);
    }

    /**
     * {@inheritDoc
     */
    public function getFactoryId(string $host): string
    {
        return $this->getString($host, SNMPObjectId::SNMP_PRINTER_FACTORY_ID);
    }

    /**
     * {@inheritDoc
     */
    public function getVendorName(string $host): string
    {
        return $this->getString($host, SNMPObjectId::SNMP_PRINTER_VENDOR_NAME);
    }

    /**
     * {@inheritDoc
     */
    public function getSerialNumber(string $host): string
    {
        return $this->getString($host, SNMPObjectId::SNMP_PRINTER_SERIAL_NUMBER);
    }

    /**
     * {@inheritDoc
     */
    public function getPrintedPages(string $host): int
    {
        $originalQuickPrint = snmp_get_quick_print();

        snmp_set_quick_print(true);

        $value = $this->getInt($host, SNMPObjectId::SNMP_NUMBER_OF_PRINTED_PAPERS);

        snmp_set_quick_print($originalQuickPrint);

        return $value;
    }

    /**
     * {@inheritDoc
     */
    public function getCartridgeType(string $host, string $color): string
    {
        $type = $this->getType($host);
        Assert::oneOf($type, PrinterType::values());

        if ($type === PrinterType::MONO) {
            return $this->getString($host, SNMPObjectId::SNMP_SUB_UNIT_TYPE_SLOT_1);
        }

        if ($type === PrinterType::COLOR) {
            $colors = [
                PrinterColor::CYAN => SNMPObjectId::SNMP_SUB_UNIT_TYPE_SLOT_1,
                PrinterColor::MAGENTA => SNMPObjectId::SNMP_SUB_UNIT_TYPE_SLOT_2,
                PrinterColor::YELLOW => SNMPObjectId::SNMP_SUB_UNIT_TYPE_SLOT_3,
                PrinterColor::BLACK => SNMPObjectId::SNMP_SUB_UNIT_TYPE_SLOT_4
            ];

            Assert::oneOf($color, $colors);

            return $this->getString($host, $colors[$color]);
        }

        return 'Unknown';
    }

    /**
     * {@inheritDoc
     */
    public function getTonerLevel(string $host, string $color): float
    {
        $type = $this->getType($host);
        Assert::oneOf($type, PrinterType::values());

        $max = 1;
        $actual = 1;

        if ($type === PrinterType::MONO) {
            $max = $this->getInt($host, SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_1);
            $actual = $this->getInt($host, SNMPObjectId::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_1);
        }

        if ($type === PrinterType::COLOR) {
            $levels = [
                PrinterColor::CYAN => [
                    'max' => SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_1,
                    'actual' => SNMPObjectId::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_1
                ],
                PrinterColor::MAGENTA => [
                    'max' => SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_2,
                    'actual' => SNMPObjectId::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_2
                ],
                PrinterColor::YELLOW => [
                    'max' => SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_3,
                    'actual' => SNMPObjectId::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_3
                ],
                PrinterColor::BLACK => [
                    'max' => SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_4,
                    'actual' => SNMPObjectId::SNMP_MARKER_SUPPLIES_ACTUAL_CAPACITY_SLOT_4
                ]
            ];

            Assert::oneOf($color, $levels);

            $level = $levels[$color];
            $max = $level['max'];
            $actual = $level['actual'];
        }

        return $actual /  $max;
    }

    /**
     * {@inheritDoc}
     */
    public function getDrumLevel(string $host): float
    {
        $type = $this->getType($host);
        Assert::oneOf($type, PrinterType::values());

        $max = 1;
        $actual = 1;

        if ($type === PrinterType::MONO) {
            $max = $this->getInt($host, SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_2);
            $actual = $this->getInt($host, SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_2);
        }

        if ($type === PrinterType::COLOR) {
            $max = $this->getInt($host, SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_5);
            $actual = $this->getInt($host, SNMPObjectId::SNMP_MARKER_SUPPLIES_MAX_CAPACITY_SLOT_5);
        }

        return $actual / $max;
    }
}