<?php

namespace App\Printing;

use Carbon\CarbonInterval;
use Smalot\Cups\Model\PrinterInterface;
use App\Entities\Printer as PrinterEntity;

class Printer implements \JsonSerializable
{
    /**
     * @var string Serial number
     */
    protected $sn;

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

    /** @var PrinterEntity */
    protected $entity;

    /**
     * PrintManager constructor.
     * @param PrinterInterface $cupsPrinter
     * @param Checker $checker
     * @param PrinterEntity|null $entity
     * @throws \Exception
     */
    public function __construct(
        PrinterInterface $cupsPrinter,
        Checker $checker,
        PrinterEntity $entity = null
    ) {
        $this->fillAttributes($cupsPrinter, $checker);

        $this->entity = $entity;
    }

    public function getSn()
    {
        return $this->sn;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        if (isset($this->entity) && !empty($this->entity->getLabel())) {
            return $this->entity->getLabel() . ' (' . $this->name . ')';
        }

        return $this->name;
    }

    public function getCupsUri()
    {
        return $this->cupsURI;
    }

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
        $this->ip = parse_url(
            $cups->getAttributes()['device-uri'][0],
            PHP_URL_HOST
        );

        $snmp->setIPAddress($this->ip);

        $this->sn = $snmp->getFactoryId();

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
            'name' => $this->getName(),
            'cupsURI' => $this->getCupsUri(),
            'ip' => $this->ip,
            'sn' => $this->getSn(),
            'uptime' => $this->uptime
        ];
    }
}
