<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\PrinterObjectAttribute;
use Illuminate\Support\Collection;
use ReflectionException;
use Smalot\Cups\Manager\PrinterManager;

class CupsPrinterProvider implements PrinterProviderInterface
{
    /** @var PrinterManager */
    private $cups;

    /**
     * CupsPrinterProvider constructor.
     * @param PrinterManager $printerManager
     */
    public function __construct(PrinterManager $printerManager)
    {
        $this->cups = $printerManager;
    }

    /**
     * {@inheritDoc}
     *
     * @throws ReflectionException
     */
    public function getPrinters(): Collection
    {
        $attributes = PrinterObjectAttribute::getAttributes();
        $printers = $this->cups->getList();

        return new Collection($printers);
    }
}