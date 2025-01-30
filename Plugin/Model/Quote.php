<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */
/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Model;

use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\DisposableEvents;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Quote\Model\Quote as QuoteTarget;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class Quote
{
    /**
     * @param Config $config
     * @param Session $session
     * @param RequestInterface $request
     */
    public function __construct(
        private readonly Config           $config,
        private readonly Session          $session,
        private readonly RequestInterface $request,
    ) {
    }

    /**
     * Before Plugin to send GTM event when removing product from cart page
     *
     * @param QuoteTarget $subject
     * @param int $itemId
     *
     * @return array<mixed>
     * @see \Magento\Quote\Model\Quote::removeItem
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    public function beforeRemoveItem(QuoteTarget $subject, int $itemId): array
    {
        if (!$this->config->isGtmCheckoutFlowProductRemovedFromCartEnabled()) {
            return [$itemId];
        }

        $fullActionName = $this->request->getFullActionName(); // @phpstan-ignore-line

        if ($fullActionName == 'checkout_cart_delete') {
            $item = $subject->getItemById($itemId);

            if ($item && $item->getId()) {
                $data = [
                    'item_id' => $item->getItemId(),
                    'name' => $item->getName(),
                    'price' => $item->getPriceInclTax(),
                    'sku' => $item->getSku(),
                    'qty' => (int)$item->getQty()
                ];

                $options = [];
                $product = $item->getProduct();
                $itemOptions = $product->getTypeInstance()->getOrderOptions($product);

                // if configurable
                if (array_key_exists('attributes_info', $itemOptions)) {
                    foreach ($itemOptions['attributes_info'] as $attribute) {
                        $code = $attribute['code'];
                        $value = $attribute['value'];
                        $options[$code] = $value;
                    }

                    if (count($options)) {
                        $data['options'] = $options;
                    }
                }

                $this->session->setDisposableGtmEventData([ // @phpstan-ignore-line
                    'event' => DisposableEvents::PRODUCT_REMOVED_FROM_CART,
                    'data' => $data
                ]);
            }
        }

        return [$itemId];
    }
}
