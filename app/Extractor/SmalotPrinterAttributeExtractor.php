<?php

declare(strict_types=1);

namespace App\Extractor;

use App\Enums\PrinterObjectAttribute;
use ReflectionException;
use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;
use Symfony\Component\HttpFoundation\ParameterBag;

class SmalotPrinterAttributeExtractor implements SmalotPrinterAttributeExtractorInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws ReflectionException
     */
    public function extract(SmalotPrinter $smalotPrinter): ParameterBag
    {
        $printerAttributes = $smalotPrinter->getAttributes();
        $attributes = new ParameterBag();

        foreach (PrinterObjectAttribute::getAttributes() as $attributeName) {
            if (in_array($attributeName, $printerAttributes, true)) {
                $attributes->set($attributeName, $printerAttributes[$attributeName]);
            }
        }

        return $attributes;
    }
}