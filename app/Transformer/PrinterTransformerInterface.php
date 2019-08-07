<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entities\Printer;
use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;

interface PrinterTransformerInterface
{
    /**
     * @param SmalotPrinter $smalotPrinter
     * @return Printer
     */
    public function transform(SmalotPrinter $smalotPrinter): Printer;
}