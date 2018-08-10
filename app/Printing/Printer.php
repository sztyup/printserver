<?php

namespace App\Printing;

use Carbon\CarbonInterval;
use Smalot\Cups\Model\PrinterInterface;
use App\Entities\Printer as PrinterEntity;

class Printer implements \JsonSerializable
{
    /**
     * @var string Name of the printer
     */
    protected $name;

    /**
     * @var string Type as color/mono
     */
    protected $type;

    /**
     * @var string URI for the CUPS service
     */
    protected $cupsURI;

    /**
     * @var string IP address or hostname of the printer
     */
    protected $ip;

    /**
     * @var CarbonInterval Uptime
     */
    protected $uptime;

    /**
     * @var string
     */
    protected $blackToner;

    /**
     * @var string
     */
    protected $printerState;

    /**
     * @var PrinterEntity
     */
    protected $entity;

    /**
     * PrintManager constructor.
     * @param PrinterInterface $cupsPrinter
     * @param Checker $checker
     * @param PrinterEntity|null $entity
     * @throws \Exception
     */
    public function __construct(PrinterInterface $cupsPrinter, Checker $checker, PrinterEntity $entity = null) {
        $this->fillAttributes($cupsPrinter, $checker);

        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if (isset($this->entity) && !empty($this->entity->getLabel())) {
            return $this->entity->getLabel() . ' (' . $this->name . ')';
        }

        return $this->name;
    }

    /**
     * @return string
     */
    public function getCupsUri(): ?string
    {
        return $this->cupsURI;
    }

    /**
     * @return string
     */
    public function getBlackToner(): ?string
    {
        return $this->blackToner;
    }

    /**
     * @return string
     */
    public function getPrinterState(): ?string
    {
        return $this->printerState;
    }

    /**
     * @return PrinterEntity|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param PrinterInterface $cups
     * @param Checker $snmp
     * @throws \Exception
     */
    protected function fillAttributes(PrinterInterface $cups, Checker $snmp)
    {
        $this->name = $cups->getName();
        $this->cupsURI = $cups->getUri();
        $this->ip = parse_url($cups->getAttributes()['device-uri'][0], PHP_URL_HOST); // Maybe wrong but idk
        $this->printerState = $cups->getAttributes()['printer-state'][0];

        $snmp->setIPAddress($this->ip);

        $this->type = $snmp->getTypeOfPrinter();
        $this->blackToner = $snmp->getBlackTonerLevel();
        $this->uptime = $snmp->getUptime();
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->entity->getId(),
            'label' => $this->entity->getLabel(),
            'name' => $this->name,
            'type' => $this->type,
            'cupsURI' => $this->cupsURI,
            'ip' => $this->ip,
            'uptime' => $this->uptime,
            'ink' => $this->blackToner,
            'busy' => $this->printerState == 'idle'
        ];
    }
}
