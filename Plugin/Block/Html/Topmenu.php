<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Block\Html;

use AcidUnit\GoogleTagManager\Block\Base;
use Magento\Theme\Block\Html\Topmenu as TopmenuTarget;
use Magento\Framework\Serialize\Serializer\Json;

class Topmenu
{
    /**
     * @param Base $base
     * @param Json $serializer
     */
    public function __construct(
        private readonly Base $base,
        private readonly Json $serializer
    ) {
    }

    /**
     * After plugin to generate <script/> tag which adds window.acidTopMenu property with top menu data
     *
     * @param TopmenuTarget $subject
     * @param string $result
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetHtml(
        TopmenuTarget $subject,
        string $result,
        string $outermostClass = '',
        string $childrenWrapClass = '',
        int $limit = 0
    ): string {
        $menu = $subject->getMenu();
        $menuData = $this->base->getTopMenuData($menu);
        $menuSerialized = (string)$this->serializer->serialize($menuData);

        $script = "<script>window.acidTopMenuData=$menuSerialized;</script>";

        return $result . $script;
    }
}
