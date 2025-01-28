<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection UsingHelperClassInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Helper;

use AcidUnit\Admin\Helper\AdminAbstractTable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Magento\Framework\Serialize\Serializer\Json;

class AdminCustomPagesLoad extends AdminAbstractTable
{
    /**
     * @param Random $mathRandom
     * @param Json|null $serializer
     */
    public function __construct(
        private readonly Random $mathRandom,
        Json $serializer = null
    ) {
        parent::__construct(
            $serializer
        );
    }

    /**
     * Check whether value is in form retrieved by _encodeArrayFieldValue()
     *
     * @param string|array<mixed> $value
     * @return bool
     */
    protected function isEncodedArrayFieldValue(array|string $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        unset($value['__empty']);

        foreach ($value as $row) {
            if (!is_array($row)
                || !array_key_exists('enabled', $row)
                || !array_key_exists('url', $row)
                || !array_key_exists('event', $row)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Encode value to be used in
     * \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param array<mixed> $value
     * @return array<mixed>
     * @throws LocalizedException
     */
    protected function encodeArrayFieldValue(array $value): array
    {
        $result = [];
        unset($value['__empty']);

        foreach ($value as $row) {
            $resultId = $this->mathRandom->getUniqueHash('_');

            $result[$resultId] = [
                'enabled' => $row['enabled'],
                'url' => $row['url'],
                'event' => $row['event']
            ];
        }

        return $result;
    }

    /**
     * Decode value from used in
     * \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
     *
     * @param array<mixed> $value
     * @return array<mixed>
     */
    protected function decodeArrayFieldValue(array $value): array
    {
        $result = [];
        unset($value['__empty']);

        foreach ($value as $key => $row) {
            if (!is_array($row)
                || !array_key_exists('enabled', $row)
                || !array_key_exists('url', $row)
                || !array_key_exists('event', $row)
            ) {
                continue;
            }

            $result[$key] = [
                'enabled' => $row['enabled'],
                'url' => $row['url'],
                'event' => $row['event']
            ];
        }

        return $result;
    }
}
