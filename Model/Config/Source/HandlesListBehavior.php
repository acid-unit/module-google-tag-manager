<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class HandlesListBehavior implements OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array<array>
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 0, 'label' => __('Exclude')],
            ['value' => 1, 'label' => __('Include')],
        ];
    }
}
