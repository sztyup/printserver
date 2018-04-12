<?php

namespace App\Printing;

use Carbon\CarbonInterval;
use Smalot\Cups\Model\PrinterInterface;

class Printer
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

    /**
     * PrintManager constructor.
     * @param PrinterInterface $cupsPrinter
     * @param Checker $checker
     * @throws \Exception
     */
    public function __construct(
        PrinterInterface $cupsPrinter,
        Checker $checker
    ) {
        $this->fillAttributes($cupsPrinter, $checker);
    }

    public function getSn()
    {
        return $this->sn;
    }

    public function getType()
    {
        return $this->type;
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
}
