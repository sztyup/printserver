<?php

declare(strict_types=1);

namespace App\Model;

use App\Entities\Printer;

class NetworkPrinter
{
    /** @var Printer */
    protected $printer;

    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var string */
    protected $ip;

    /** @var mixed */
    protected $uptime;

    /** @var string */
    protected $cyan;

    /** @var string */
    protected $magenta;

    /** @var string */
    protected $yellow;

    /** @var string */
    protected $black;

    /** @var string */
    protected $state;

    /**
     * @return Printer
     */
    public function getPrinter(): Printer
    {
        return $this->printer;
    }

    /**
     * @param Printer $printer
     */
    public function setPrinter(Printer $printer): void
    {
        $this->printer = $printer;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getUptime()
    {
        return $this->uptime;
    }

    /**
     * @param $uptime
     */
    public function setUptime($uptime): void
    {
        $this->uptime = $uptime;
    }

    /**
     * @return string
     */
    public function getCyan(): ?string
    {
        return $this->cyan;
    }

    /**
     * @param string $cyan
     */
    public function setCyan(?string $cyan): void
    {
        $this->cyan = $cyan;
    }

    /**
     * @return string
     */
    public function getMagenta(): ?string
    {
        return $this->magenta;
    }

    /**
     * @param string $magenta
     */
    public function setMagenta(?string $magenta): void
    {
        $this->magenta = $magenta;
    }

    /**
     * @return string
     */
    public function getYellow(): ?string
    {
        return $this->yellow;
    }

    /**
     * @param string $yellow
     */
    public function setYellow(?string $yellow): void
    {
        $this->yellow = $yellow;
    }

    /**
     * @return string
     */
    public function getBlack(): ?string
    {
        return $this->black;
    }

    /**
     * @param string $black
     */
    public function setBlack(?string $black): void
    {
        $this->black = $black;
    }

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }
}