<?php

namespace App\Printing;

use Doctrine\ORM\EntityManager;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Model\Job;
use App\Entities\Printer as PrinterEntity;

class Manager
{
    protected $em;

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
     * @param EntityManager $em
     * @throws \Exception
     */
    public function __construct(
        FilesystemManager $filesystemManager,
        Checker $checker,
        PrinterManager $cupsManager,
        JobManager $jobManager,
        EntityManager $em
    ) {
        $this->em = $em;
        $this->cups = $cupsManager;
        $this->checker = $checker;
        $this->storage = $filesystemManager->drive();
        $this->jobManager = $jobManager;

        $this->load();
    }

    protected function load()
    {
        $this->printers = Collection::make(
            $this->em->getRepository(PrinterEntity::class)->findAll()
        );
    }

    /**
     * @param PrinterManager $cups
     * @param Checker $snmp
     * @return array
     * @throws \Exception
     */
    public function discover()
    {
        $printers = $this->cups->getList([
            'printer-uri-supported',
            'printer-name',
            'printer-state',
            'printer-location',
            'printer-info',
            'printer-type',
            'printer-icons',
            'device-uri'
        ]);

        /** @var Printer[] $result */
        $result = [];

        foreach ($printers as $printer) {
            $result[$new->getSn()] = $new = new Printer($printer, $this->checker);
        }

        $repo = $this->em->getRepository(PrinterEntity::class);
        foreach ($result as $key => $printer) {
            $entity = $repo->findOneBy([
                'sn' => $printer->getSn()
            ]);

            if ($entity == null) {
                $entity = PrinterEntity::create([
                    'sn' => $printer->getSn(),
                    'type' => $printer->getType()
                ]);

                $this->em->persist($entity);
            } else {
                unset($result[$key]);
            }
        }

        $this->em->flush();

        return $result;
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
