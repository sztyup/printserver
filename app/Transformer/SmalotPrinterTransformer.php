<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Extractor\SmalotPrinterAttributeExtractorInterface;
use App\Factory\NetworkPrinterFactoryInterface;
use App\Model\NetworkPrinter;
use Smalot\Cups\Model\PrinterInterface as SmalotPrinter;

class SmalotPrinterTransformer implements SmalotPrinterTransformerInterface
{
    /** @var NetworkPrinterFactoryInterface */
    private $networkPrinterFactory;

    /** @var SmalotPrinterAttributeExtractorInterface */
    private $attributeExtractor;

    /**
     * SmalotPrinterTransformer constructor.
     * @param NetworkPrinterFactoryInterface $networkPrinterFactory
     * @param SmalotPrinterAttributeExtractorInterface $attributeExtractor
     */
    public function __construct(
        NetworkPrinterFactoryInterface $networkPrinterFactory,
        SmalotPrinterAttributeExtractorInterface $attributeExtractor
    ) {
        $this->networkPrinterFactory = $networkPrinterFactory;
        $this->attributeExtractor = $attributeExtractor;
    }

    /**
     * {@inheritDoc}
     */
    public function transform(SmalotPrinter $smalotPrinter): NetworkPrinter
    {
        $networkPrinter = $this->networkPrinterFactory->create();
        $networkPrinter->setName($smalotPrinter->getName());

        $attributes = $this->attributeExtractor->extract($smalotPrinter);

        $networkPrinter->setType();
    }
}