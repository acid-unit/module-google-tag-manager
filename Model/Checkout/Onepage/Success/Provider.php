<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Checkout\Onepage\Success;

use AcidUnit\GoogleTagManager\Api\DataProviderInterface;
use AcidUnit\GoogleTagManager\Model\Config;
use AcidUnit\GoogleTagManager\Model\ProductDataProvider;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavModel;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Model\Entity\Attribute\Option;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order as OrderModel;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class Provider implements DataProviderInterface, ArgumentInterface
{
    /**
     * @var OrderModel|null
     */
    private ?OrderModel $order = null;

    /**
     * @param CheckoutSession $checkoutSession
     * @param ProductDataProvider $productDataProvider
     * @param AttributeOptionInterfaceFactory $optionFactory
     * @param EavModel $eavModel
     * @param Config $config
     */
    public function __construct(
        private readonly CheckoutSession                 $checkoutSession,
        private readonly ProductDataProvider             $productDataProvider,
        private readonly AttributeOptionInterfaceFactory $optionFactory,
        private readonly EavModel                        $eavModel,
        private readonly Config                          $config
    ) {
    }

    /**
     * Get Data
     *
     * @return array<mixed>
     */
    public function getData(): array
    {
        if (!$this->config->isGtmCheckoutFlowPurchaseDoneEnabled()) {
            return [];
        }

        $order = $this->getOrder();

        if (!$order->getId()) {
            return [];
        }

        return [
            'order_data' => [
                'id' => (string)$order->getIncrementId(),
                'grand_total' => (float)$order->getGrandTotal(),
                'shipping' => (float)$order->getShippingAmount() ?: '',
                'discount' => (float)$order->getDiscountAmount() ?: '',
                'tax' => (float)$order->getTaxAmount() ?: '',
                'revenue' => $this->getRevenue(),
                'coupon' => $order->getCouponCode() ?: ''
            ],
            'products' => $this->getProductsData()
        ];
    }

    /**
     * Get revenue (grand total - shipping)
     *
     * @return float
     */
    private function getRevenue(): float
    {
        $order = $this->getOrder();

        return (float)$order->getGrandTotal() - (float)$order->getShippingAmount();
    }

    /**
     * Get products data
     *
     * @return array<mixed>
     * @noinspection PhpDeprecationInspection
     */
    private function getProductsData(): array
    {
        $result = [];

        /** @var OrderItem $item */
        foreach ($this->getOrder()->getAllVisibleItems() as $item) {
            $product = $item->getProduct();

            $productInfo = $this->productDataProvider->getProductData($product);
            $productInfo['qty'] = (int)$item->getQtyOrdered();

            if ($product->getTypeId() == Configurable::TYPE_CODE) {
                $options = [];
                $itemOptions = $item->getProductOptions();

                if (array_key_exists('attributes_info', $itemOptions)) {
                    foreach ($itemOptions['attributes_info'] as $attribute) {
                        /** @var Option $option */
                        $option = $this->optionFactory->create();
                        $option->load($attribute['option_value']);
                        $attributeId = $option->getAttributeId();
                        $attributeCode = $this->eavModel->load($attributeId)->getAttributeCode();

                        $value = $attribute['value'];
                        $options[$attributeCode] = $value;
                    }

                    if (count($options)) {
                        $productInfo['options'] = $options;
                    }
                }
            }

            $result[] = $productInfo;
        }

        return $result;
    }

    /**
     * Get Order
     *
     * @return OrderModel
     */
    private function getOrder(): OrderModel
    {
        if (!$this->order) {
            $this->order = $this->checkoutSession->getLastRealOrder();
        }

        return $this->order;
    }
}
