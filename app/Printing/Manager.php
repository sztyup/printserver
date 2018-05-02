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
    /** @var EntityManager */
    protected $em;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem */
    protected $storage;

    /** @var Printer[]|Collection */
    protected $printers;

    /** @var Checker */
    protected $checker;

    /** @var PrinterManager */
    protected $cups;

    /** @var JobManager */
    protected $jobManager;

    const DEFAULT_USERNAME = 'printserver';

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

        $this->discover();
        $this->load();
    }

    /**
     *
     */
    protected function load()
    {
        $this->printers = Collection::make(
            $this->em->getRepository(PrinterEntity::class)->findAll()
        )->map(function (PrinterEntity $printer) {
            return new Printer(
                $this->cups->findByUri($printer->getCupsUri()),
                $this->checker,
                $printer
            );
        });
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
            $result[] = new Printer($printer, $this->checker);
        }

        $repo = $this->em->getRepository(PrinterEntity::class);
        foreach ($result as $key => $printer) {
            /** @var PrinterEntity $entity */
            $entity = $repo->findOneBy([
                'sn' => $printer->getSn()
            ]);

            if ($entity == null) {
                $entity = PrinterEntity::create([
                    'sn' => $printer->getSn(),
                    'type' => $printer->getType() ?? 0,
                    'cupsUri' => $printer->getCupsUri(),
                    'label' => 'Unknown printer'
                ]);

                $this->em->persist($entity);
            } else {
                $entity->setCupsUri($printer->getCupsUri());

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

    public function getPrinterBySn(string $sn)
    {
        return $this->printers->filter(function (Printer $printer) use ($sn) {
            return $printer->getSn() == $sn;
        })->first();
    }

    /**
     * @param Printer $printer
     * @param $file
     * @param int $copies
     * @param string $username
     * @return bool
     */
    public function printFile(Printer $printer, $file, $copies = 1, $username = self::DEFAULT_USERNAME)
    {
        $printer = $this->cups->findByUri($printer->getCupsUri());

        $job = new Job();
        $job->setName(pathinfo($file, PATHINFO_FILENAME));
        $job->setUsername($username);
        $job->setCopies($copies);
        $job->addFile($file);
        $job->addAttribute('media', 'A4');
        $job->addAttribute('fit-to-page', true);
        $job->setSides(Job::SIDES_ONE_SIDED);

        return $this->jobManager->send($printer, $job);
    }

    public function printText(Printer $printer, $text, $copies = 1, $username = self::DEFAULT_USERNAME)
    {
        $printer = $this->cups->findByUri($printer->getCupsUri());

        $job = new Job();
        $job->setUsername($username);
        $job->setCopies($copies);
        $job->addText($text);
        $job->addAttribute('media', 'A4');
        $job->addAttribute('fit-to-page', true);
        $job->setSides(Job::SIDES_ONE_SIDED);

        return $this->jobManager->send($printer, $job);
    }
}
