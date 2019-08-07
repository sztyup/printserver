<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entities\Printer;

class PrinterFactory implements PrinterFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(): Printer
    {
        $printer = new Printer();

        return $printer;
    }
}