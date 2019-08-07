<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Factory\NetworkPrinterFactoryInterface;
use App\Mediator\HostedPrinterMediatorInterface;
use App\Mediator\PrinterMediatorInterface;
use App\Model\NetworkPrinter;
use App\Providers\PrinterProviderInterface;
use App\Repository\PrinterRepositoryInterface;
use Illuminate\Console\Command;
use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListPrintersCommand extends Command
{
    /** @var string */
    protected $name = 'printers:list';

    /** @var PrinterProviderInterface */
    private $provider;

    /** @var PrinterRepositoryInterface */
    private $repository;

    /** @var NetworkPrinterFactoryInterface */
    private $networkPrinterFactory;

    /** @var PrinterMediatorInterface */
    private $printerMediator;

    /** @var HostedPrinterMediatorInterface */
    private $hostedPrinterMediator;

    /**
     * ListPrintersCommand constructor.
     * @param PrinterProviderInterface $provider
     * @param PrinterRepositoryInterface $repository
     * @param NetworkPrinterFactoryInterface $networkPrinterFactory
     * @param PrinterMediatorInterface $printerMediator
     * @param HostedPrinterMediatorInterface $hostedPrinterMediator
     */
    public function __construct(
        PrinterProviderInterface $provider,
        PrinterRepositoryInterface $repository,
        NetworkPrinterFactoryInterface $networkPrinterFactory,
        PrinterMediatorInterface $printerMediator,
        HostedPrinterMediatorInterface $hostedPrinterMediator
    ) {
        parent::__construct();

        $this->provider = $provider;
        $this->repository = $repository;
        $this->networkPrinterFactory = $networkPrinterFactory;
        $this->printerMediator = $printerMediator;
        $this->hostedPrinterMediator = $hostedPrinterMediator;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $printers = $this->provider->getPrinters();

        if ($printers->isEmpty()) {
            $this->info('There are no printers connected');
            return;
        }

        dump($printers);

        $this->hostedPrinterMediator->setMediator($this->printerMediator);

        $networkPrinters = $printers->map(function (SmalotPrinter $smalotPrinter) {
            $printer = $this->repository->findOneByCupsURI($smalotPrinter->getUri());

            if (!$printer) {
                return null;
            }

            $networkPrinter = $this->networkPrinterFactory->create();
            $networkPrinter->setPrinter($printer);

            return $networkPrinter;
        })->filter(static function (?NetworkPrinter $printer) {
            return $printer !== null;
        });

        dump($networkPrinters);
    }
}