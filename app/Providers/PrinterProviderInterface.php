<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Collection;
use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;

interface PrinterProviderInterface
{
    /**
     * @return Collection|SmalotPrinter[]
     */
    public function getPrinters(): Collection;
}