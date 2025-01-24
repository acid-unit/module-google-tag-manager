<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model;

use AcidUnit\PageContentUpdate\Api\PageContentUpdateInterface;
use AcidUnit\PageContentUpdate\Model\PageContentUpdateProcessor;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Math\Random;

class ProductDataUpdateHandler implements PageContentUpdateInterface, ArgumentInterface
{
    /**
     * @var string
     */
    private string $placeholder;

    /**
     * @param ProductDataStorage $productDataStorage
     * @param PageContentUpdateProcessor $pageContentUpdateProcessor
     * @param Json $serializer
     * @param Random $mathRandom
     * @throws LocalizedException
     */
    public function __construct(
        private readonly ProductDataStorage         $productDataStorage,
        private readonly PageContentUpdateProcessor $pageContentUpdateProcessor,
        private readonly Json                       $serializer,
        private readonly Random                     $mathRandom
    ) {
        $this->pageContentUpdateProcessor->addUpdateHandler($this);
        $this->placeholder = $this->mathRandom->getUniqueHash('acid_gtm_');
    }

    /**
     * Placeholder is rendered in the .phtml template which gets replaced
     *
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent(): string
    {
        return (string)$this->serializer->serialize($this->productDataStorage->getAllProductsData());
    }
}
