<?php

namespace App\Printing;

use Smalot\Cups\Model\PrinterInterface;

class Printer
{
    protected $sn;

    protected $name;

    protected $type;

    protected $cupsURI;

    protected $ip;

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

        $this->sn = $snmp->getSerialNumber();
    }
}
