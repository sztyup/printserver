<?php

declare(strict_types=1);

namespace App\Mediator;

interface PrinterMediatorInterface
{
    /**
     * @param string $host
     * @return string
     */
    public function getType(string $host): string;

    /**
     * @param string $host
     * @return int
     */
    public function getUptime(string $host): int;

    /**
     * @param string $host
     * @return string
     */
    public function getFactoryId(string $host): string;

    /**
     * @param string $host
     * @return string
     */
    public function getVendorName(string $host): string;

    /**
     * @param string $host
     * @return string
     */
    public function getSerialNumber(string $host): string;

    /**
     * @param string $host
     * @return int
     */
    public function getPrintedPages(string $host): int;

    /**
     * @param string $host
     * @param string $color
     * @return string
     */
    public function getCartridgeType(string $host, string $color): string;

    /**
     * @param string $host
     * @param string $color
     * @return float
     */
    public function getTonerLevel(string $host, string $color): float;

    /**
     * @param string $host
     * @return float
     */
    public function getDrumLevel(string $host): float;
}