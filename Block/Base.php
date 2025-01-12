<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

namespace AcidUnit\GoogleTagManager\Block;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Serialize\Serializer\Json;

class Base extends Template
{
    /**
     * @var string
     */
    private string $pageHandle = '';

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param Context $context
     * @param Json $serializer
     * @param SessionFactory $sessionFactory
     * @param array<mixed> $data
     */
    public function __construct(
        Context                         $context,
        private readonly Json           $serializer,
        private readonly SessionFactory $sessionFactory,
        array                           $data = []
    ) {
        parent::__construct($context, $data);

        $this->request = $context->getRequest();
    }

    /**
     * Get top menu data array
     *
     * @param Node $menu
     * @return array<mixed>
     */
    public function getTopMenuData(Node $menu): array
    {
        $result = [];

        foreach ($menu->getChildren() as $key => $child) {
            $result[$key] = $child->getData();

            if ($child->hasChildren()) {
                $result[$key]['children'] = $this->getTopMenuData($child);
            }
        }

        return $result;
    }

    /**
     * Get product ID HTML
     *
     * @param Product $product
     * @return string
     */
    public function getProductIdHtml(Product $product): string
    {
        return "<span class='acid-product-id' data-id='{$product->getId()}'></span>";
    }

    /**
     * Get page handle
     *
     * @return string
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function getPageHandle(): string
    {
        if (!$this->pageHandle) {
            $this->pageHandle = $this->request->getFullActionName(); // @phpstan-ignore-line
        }

        return $this->pageHandle;
    }

    /**
     * Get page data
     *
     * @return string
     */
    public function getSerializedPageData(): string
    {
        $data = array_merge(
            $this->getGtmData()
        );

        return $this->serializer->serialize($data);
    }

    /**
     * Get GTM session data
     *
     * @return array<mixed>
     * @noinspection PhpUndefinedMethodInspection
     */
    private function getGtmData(): array
    {
        $customerSession = $this->sessionFactory->create();
        $gtmData = $customerSession->getGtmData() ?: []; // @phpstan-ignore-line
        $customerSession->unsGtmData(); // @phpstan-ignore-line

        $userType = $customerSession->isLoggedIn() ? 'registered' : 'new';
        $gtmData['userType'] = $userType;

        return $gtmData;
    }
}
