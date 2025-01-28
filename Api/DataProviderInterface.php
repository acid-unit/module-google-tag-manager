<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Api;

/**
 * Interface for GTM data provider
 *
 * @api
 */
interface DataProviderInterface
{
    /**
     * Get Data
     *
     * @return array<mixed>
     */
    public function getData(): array;
}
