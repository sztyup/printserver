<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Model\NetworkPrinter;
use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;

interface SmalotPrinterTransformerInterface
{
    /**
     * @param SmalotPrinter $smalotPrinter
     * @return NetworkPrinter
     */
    public function transform(SmalotPrinter $smalotPrinter): NetworkPrinter;
}