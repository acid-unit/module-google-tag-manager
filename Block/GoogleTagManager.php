<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

namespace AcidUnit\GoogleTagManager\Block;

use AcidUnit\Core\Api\DataProviderInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Layout;

class GoogleTagManager extends Template
{
    /**
     * @var array<object>
     */
    private array $dataProviders;

    /**
     * @var string
     */
    private string $pageMainHandle = '';

    /**
     * @var array<string>
     */
    private array $allPageHandles = [];

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param Context $context
     * @param Json $serializer
     * @param SessionFactory $sessionFactory
     * @param Layout $layout
     * @param array<object> $dataProviders
     * @param array<mixed> $data
     */
    public function __construct(
        Context                         $context,
        private readonly Json           $serializer,
        private readonly SessionFactory $sessionFactory,
        private readonly Layout         $layout,
        array                           $dataProviders = [],
        array                           $data = []
    ) {
        parent::__construct($context, $data);

        $this->request = $context->getRequest();
        $this->dataProviders = $dataProviders;
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
    public function getPageMainHandle(): string
    {
        if (!$this->pageMainHandle) {
            $this->pageMainHandle = $this->request->getFullActionName(); // @phpstan-ignore-line
        }

        return $this->pageMainHandle;
    }

    /**
     * Get all page handles
     *
     * @return string
     */
    public function getAllPageHandles(): string
    {
        if (!$this->allPageHandles) {
            $this->allPageHandles = $this->layout->getUpdate()->getHandles();
        }

        return $this->serializer->serialize($this->allPageHandles);
    }

    /**
     * Get page data
     *
     * @return string
     */
    public function getSerializedPageData(): string
    {
        $data = array_merge(
            $this->getCustomerType(),
            $this->getDisposableSessionData(),
            $this->getDataProviderData(),
        );

        return $this->serializer->serialize($data);
    }

    /**
     * Get data from data provider set in di.xml
     *
     * @return array<array>
     */
    private function getDataProviderData(): array
    {
        $handle = $this->getPageMainHandle();
        /** @var DataProviderInterface|null $dataProvider */
        $dataProvider = $this->dataProviders[$handle] ?? null;

        if (!$dataProvider) {
            return [];
        }

        return ['provider' => $dataProvider->getData()];
    }

    /**
     * Get disposable GTM event data from session
     *
     * @return array<array>
     * @noinspection PhpUndefinedMethodInspection
     */
    private function getDisposableSessionData(): array
    {
        $customerSession = $this->sessionFactory->create();

        $data = $customerSession->getDisposableGtmEventData() ?: []; // @phpstan-ignore-line
        $customerSession->unsDisposableGtmEventData(); // @phpstan-ignore-line

        return count($data) ? ['disposable' => $data] : [];
    }

    /**
     * Get GTM customer type from session
     *
     * @return array<string>
     */
    private function getCustomerType(): array
    {
        $data = [];
        $customerSession = $this->sessionFactory->create();
        $userType = $customerSession->isLoggedIn() ? 'registered' : 'new';
        $data['user_type'] = $userType;

        return $data;
    }
}
