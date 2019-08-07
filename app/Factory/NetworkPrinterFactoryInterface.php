<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\NetworkPrinter;

interface NetworkPrinterFactoryInterface
{
    /**
     * @return NetworkPrinter
     */
    public function create(): NetworkPrinter;
}