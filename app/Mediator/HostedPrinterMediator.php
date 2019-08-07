<?php

declare(strict_types=1);

namespace App\Mediator;

class HostedPrinterMediator implements HostedPrinterMediatorInterface
{
    /** @var PrinterMediatorInterface */
    private $mediator;

    /** @var string */
    private $host;

    /**
     * {@inheritDoc}
     */
    public function getMediator(): PrinterMediatorInterface
    {
        return $this->mediator;
    }

    /**
     * {@inheritDoc}
     */
    public function setMediator(PrinterMediatorInterface $mediator): void
    {
        $this->mediator = $mediator;
    }

    /**
     * {@inheritDoc}
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * {@inheritDoc}
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return $this->mediator->getType($this->host);
    }

    /**
     * {@inheritDoc}
     */
    public function getUptime(): int
    {
        return $this->mediator->getUptime($this->host);
    }

    /**
     * {@inheritDoc}
     */
    public function getFactoryId(): string
    {
        return $this->mediator->getFactoryId($this->host);
    }

    /**
     * {@inheritDoc}
     */
    public function getVendorName(): string
    {
        return $this->mediator->getVendorName($this->host);
    }

    /**
     * {@inheritDoc}
     */
    public function getSerialNumber(): string
    {
        return $this->mediator->getSerialNumber($this->host);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrintedPages(): int
    {
        return $this->mediator->getPrintedPages($this->host);
    }

    /**
     * {@inheritDoc}
     */
    public function getCartridgeType(string $color): string
    {
        return $this->mediator->getCartridgeType($this->host, $color);
    }

    /**
     * {@inheritDoc}
     */
    public function getTonerLevel(string $color): float
    {
        return $this->mediator->getTonerLevel($this->host, $color);
    }

    /**
     * {@inheritDoc}
     */
    public function getDrumLevel(): float
    {
        return $this->mediator->getDrumLevel($this->host);
    }
}