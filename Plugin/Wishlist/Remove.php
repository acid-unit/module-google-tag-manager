<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Plugin\Wishlist;

use AcidUnit\GoogleTagManager\Model\Config;
use Exception;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Wishlist\Controller\Index\Remove as RemoveTarget;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Wishlist\Helper\Data;
use Magento\Wishlist\Model\Item;
use Magento\Wishlist\Model\Product\AttributeValueProvider;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Remove extends RemoveTarget
{
    /**
     * @var AttributeValueProvider|null
     */
    private ?AttributeValueProvider $attributeValueProvider;

    /**
     * @param Config $config
     * @param Context $context
     * @param WishlistProviderInterface $wishlistProvider
     * @param Validator $formKeyValidator
     * @param AttributeValueProvider|null $attributeValueProvider
     * @noinspection ObjectManagerInspection
     */
    public function __construct(
        private readonly Config   $config,
        Context                   $context,
        WishlistProviderInterface $wishlistProvider,
        Validator                 $formKeyValidator,
        AttributeValueProvider    $attributeValueProvider = null
    ) {
        $this->attributeValueProvider = $attributeValueProvider
            ?: ObjectManager::getInstance()->get(AttributeValueProvider::class);

        parent::__construct(
            $context,
            $wishlistProvider,
            $formKeyValidator,
            $attributeValueProvider
        );
    }

    /**
     * Around Plugin to dispatch 'wishlist_remove_product' event when product is removed from wishlist
     *
     * @param RemoveTarget $subject
     * @param callable $proceed
     *
     * @return Redirect
     * @throws NotFoundException
     * @see \Magento\Wishlist\Controller\Index\Remove::execute
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     * @noinspection PhpUnusedParameterInspection
     * @noinspection ObjectManagerInspection
     * @noinspection PhpDeprecationInspection
     * @noinspection PhpUnusedLocalVariableInspection
     * @noinspection PhpCastIsUnnecessaryInspection
     */
    public function aroundExecute(RemoveTarget $subject, callable $proceed): Redirect
    {
        if (!$this->config->isGtmWishlistRemoveEnabled()) {
            return $proceed();
        }

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/*/');
        }

        $id = (int)$this->getRequest()->getParam('item');
        /** @var Item $item */
        $item = $this->_objectManager->create(Item::class)->load($id);

        if (!$item->getId()) {
            throw new NotFoundException(__('Page not found.'));
        }

        $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());

        /** @phpstan-ignore-next-line */
        if (!$wishlist) {
            throw new NotFoundException(__('Page not found.'));
        }

        try {
            $item->delete();
            $wishlist->save();

            $this->_eventManager->dispatch(
                'wishlist_remove_product',
                ['wishlist' => $wishlist, 'item' => $item]
            );

            $productName = $this->attributeValueProvider
                ->getRawAttributeValue((int)$item->getProductId(), 'name');
            $this->messageManager->addComplexSuccessMessage(
                'removeWishlistItemSuccessMessage',
                [
                    'product_name' => $productName,
                ]
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t delete the item from Wish List right now because of an error: %1.', $e->getMessage())
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('We can\'t delete the item from the Wish List right now.'));
        }

        $this->_objectManager->get(Data::class)->calculate();
        $refererUrl = $this->_redirect->getRefererUrl();

        if ($refererUrl) {
            $redirectUrl = $refererUrl;
        } else {
            $redirectUrl = $this->_redirect->getRedirectUrl($this->_url->getUrl('*/*'));
        }

        $resultRedirect->setUrl($redirectUrl);
        return $resultRedirect;
    }
}
