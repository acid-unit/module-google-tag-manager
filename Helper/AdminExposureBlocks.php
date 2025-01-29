<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */
/** @noinspection UsingHelperClassInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Helper;

use AcidUnit\Admin\Helper\AdminAbstractTableField;
use Magento\Framework\Math\Random;
use Magento\Framework\Serialize\Serializer\Json;

class AdminExposureBlocks extends AdminAbstractTableField
{
    /**
     * @param Random $mathRandom
     * @param Json $serializer
     * @param array $tableFields
     */
    public function __construct(
        Random $mathRandom,
        Json   $serializer,
        array  $tableFields = [
            'enabled',
            'selector',
            'name'
        ]
    ) {
        parent::__construct(
            $serializer,
            $mathRandom,
            $tableFields
        );
    }
}
