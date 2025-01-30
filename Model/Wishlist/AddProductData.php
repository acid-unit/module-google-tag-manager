<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Wishlist;

use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\DisposableEvents;
use AcidUnit\GoogleTagManager\Model\ProductDataProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Wishlist\Model\Item;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class AddProductData
{
    /**
     * @param Session $session
     * @param Config $config
     * @param ProductDataProvider $productDataProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Session             $session,
        private readonly Config              $config,
        private readonly ProductDataProvider $productDataProvider,
        private readonly LoggerInterface     $logger
    ) {
    }

    /**
     * Set customer data
     *
     * @param Item $item
     * @return void
     * @noinspection PhpUndefinedMethodInspection
     */
    public function setCustomerData(Item $item): void
    {
        if (!$this->config->isGtmWishlistAddEnabled()) {
            return;
        }

        try {
            $product = $this->productDataProvider->getProductData($item->getProduct());

            $this->session->setDisposableGtmEventData([ // @phpstan-ignore-line
                'event' => DisposableEvents::PRODUCT_ADDED_TO_WISHLIST,
                'data' => [
                    'products' => [$product]
                ]
            ]);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getLogMessage());
        }
    }
}
