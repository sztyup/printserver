<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Entities\Printer;
use App\Extractor\SmalotPrinterAttributeExtractorInterface;
use App\Factory\PrinterFactoryInterface;
use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;

class PrinterTransformer implements PrinterTransformerInterface
{
    /** @var PrinterFactoryInterface */
    private $printerFactory;

    /** @var SmalotPrinterAttributeExtractorInterface */
    private $attributeExtractor;

    /**
     * PrinterTransformer constructor.
     * @param PrinterFactoryInterface $printerFactory
     * @param SmalotPrinterAttributeExtractorInterface $attributeExtractor
     */
    public function __construct(
        PrinterFactoryInterface $printerFactory,
        SmalotPrinterAttributeExtractorInterface $attributeExtractor
    ) {
        $this->printerFactory = $printerFactory;
        $this->attributeExtractor = $attributeExtractor;
    }

    /**
     * {@inheritDoc}
     */
    public function transform(SmalotPrinter $smalotPrinter): Printer
    {
        $printer = $this->printerFactory->create();
        $printer->setCupsUri($smalotPrinter->getUri());

//        $printer
    }
}