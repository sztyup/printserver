<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entities\Printer;

interface PrinterFactoryInterface
{
    /**
     * @return Printer
     */
    public function create(): Printer;
}