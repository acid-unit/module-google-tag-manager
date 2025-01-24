<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Model\Product\Type;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableTarget;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class Configurable extends ConfigurableTarget
{
    /**
     * After Plugin to pass attribute code to product options
     *
     * @param ConfigurableTarget $subject
     * @param array $result
     * @param Product $product
     * @return array<mixed>
     * @noinspection PhpUnusedParameterInspection
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSelectedAttributesInfo(
        ConfigurableTarget $subject,
        array $result,
        Product $product
    ): array {
        if ($attributesOption = $product->getCustomOption('attributes')) {
            $data = $attributesOption->getValue();

            if (!$data) {
                return $result;
            }

            $usedAttributes = $product->getData($this->_usedAttributes);

            foreach ($result as &$attribute) {
                $optionId = $attribute['option_id'];
                $code = $usedAttributes[$optionId]->getProductAttribute()->getAttributeCode();
                $attribute['code'] = $code;
            }
        }
        return $result;
    }
}
