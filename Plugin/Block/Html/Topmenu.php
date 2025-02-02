<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpClassCanBeReadonlyInspection */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Block\Html;

use AcidUnit\GoogleTagManager\Block\GoogleTagManager;
use AcidUnit\GoogleTagManager\Model\Config;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Theme\Block\Html\Topmenu as TopmenuTarget;

class Topmenu
{
    /**
     * @param GoogleTagManager $gtm
     * @param Json $serializer
     * @param Config $config
     */
    public function __construct(
        private readonly GoogleTagManager $gtm,
        private readonly Json             $serializer,
        private readonly Config           $config
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
     *
     * @return string
     * @see \Magento\Theme\Block\Html\Topmenu::getHtml
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    public function afterGetHtml(
        TopmenuTarget $subject,
        string        $result,
        string        $outermostClass = '',
        string        $childrenWrapClass = '',
        int           $limit = 0
    ): string {
        if (!$this->config->isGtmClickMenuItemEnabled() &&
            !$this->config->isGtmExposureMenuCategoryEnabled()
        ) {
            /**
             * If click and hover menu item events are disabled,
             * we don't need to render 'acidTopMenuData' property
             */
            return $result;
        }

        $menu = $subject->getMenu();
        $menuData = $this->gtm->getTopMenuData($menu);
        $menuSerialized = (string)$this->serializer->serialize($menuData);

        $script = "<script>window.acidTopMenuData=$menuSerialized;</script>";

        return $result . $script;
    }
}
