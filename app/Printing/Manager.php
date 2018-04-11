<?php

namespace App\Printing;

use Illuminate\Filesystem\FilesystemManager;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Model\Job;

class Manager
{
    protected $storage;

    protected $printers;

    protected $checker;

    /** @var PrinterManager */
    protected $cups;

    /** @var JobManager */
    protected $jobManager;

    const USERNAME_TO_PRINTER = 'printserver';

    /**
     * Manager constructor.
     * @param FilesystemManager $filesystemManager
     * @param Checker $checker
     * @param PrinterManager $cupsManager
     * @param JobManager $jobManager
     * @throws \Exception
     */
    public function __construct(
        FilesystemManager $filesystemManager,
        Checker $checker,
        PrinterManager $cupsManager,
        JobManager $jobManager
    ) {
        $this->cups = $cupsManager;
        $this->checker = $checker;
        $this->storage = $filesystemManager->drive();
        $this->jobManager = $jobManager;

        $this->init($cupsManager, $checker);
    }

    /**
     * @param PrinterManager $cups
     * @param Checker $snmp
     * @throws \Exception
     */
    protected function init(PrinterManager $cups, Checker $snmp)
    {
        $printers = $cups->getList([
            'printer-uri-supported',
            'printer-name',
            'printer-state',
            'printer-location',
            'printer-info',
            'printer-type',
            'printer-icons',
            'device-uri'
        ]);

        foreach ($printers as $printer) {
            $this->printers[] = new Printer($printer, $snmp);
        }
    }

    /**
     * @return Printer[]
     * @throws \Exception
     */
    public function getPrinters()
    {
        return $this->printers;
    }

    /**
     * @param Printer $printer
     * @param $file
     * @param int $copies
     * @return bool
     */
    public function print(Printer $printer, $file, $copies = 1)
    {
        $printer = $this->cups->findByUri($printer);

        $job = new Job();
        $job->setName(pathinfo($file, PATHINFO_FILENAME));
        $job->setUsername(self::USERNAME_TO_PRINTER);
        $job->setCopies($copies);
        $job->setPageRanges('1');
        $job->addFile($file);
        $job->addAttribute('media', 'A4');
        $job->addAttribute('fit-to-page', true);
        $job->setSides(Job::SIDES_ONE_SIDED);

        return $this->jobManager->send($printer, $job);
    }
}
