<?php

declare(strict_types=1);

namespace App\Extractor;

use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;
use Symfony\Component\HttpFoundation\ParameterBag;

interface SmalotPrinterAttributeExtractorInterface
{
    /**
     * @param SmalotPrinter $smalotPrinter
     * @return ParameterBag
     */
    public function extract(SmalotPrinter $smalotPrinter): ParameterBag;
}