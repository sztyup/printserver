<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\NetworkPrinter;

class NetworkPrinterFactory implements NetworkPrinterFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(): NetworkPrinter
    {
        return new NetworkPrinter();
    }
}