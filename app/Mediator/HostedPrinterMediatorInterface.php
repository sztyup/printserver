<?php

declare(strict_types=1);

namespace App\Mediator;

interface HostedPrinterMediatorInterface
{
    /**
     * @return PrinterMediatorInterface
     */
    public function getMediator(): PrinterMediatorInterface;

    /**
     * @param PrinterMediatorInterface $mediator
     */
    public function setMediator(PrinterMediatorInterface $mediator): void;

    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @param string $host
     */
    public function setHost(string $host): void;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return int
     */
    public function getUptime(): int;

    /**
     * @return string
     */
    public function getFactoryId(): string;

    /**
     * @return string
     */
    public function getVendorName(): string;

    /**
     * @return string
     */
    public function getSerialNumber(): string;

    /**
     * @return int
     */
    public function getPrintedPages(): int;

    /**
     * @param string $color
     * @return string
     */
    public function getCartridgeType(string $color): string;

    /**
     * @param string $color
     * @return float
     */
    public function getTonerLevel(string $color): float;

    /**
     * @return float
     */
    public function getDrumLevel(): float;
}