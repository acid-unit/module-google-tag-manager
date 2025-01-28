<?php
/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/** @noinspection PhpMissingFieldTypeInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

namespace AcidUnit\GoogleTagManager\Block\Adminhtml\Form\Field;

use AcidUnit\Admin\Block\Adminhtml\Form\Field\Yesno;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class CustomPagesLoad extends AbstractFieldArray
{
    /**
     * @var Yesno
     */
    protected $yesNoRenderer;

    /**
     * Get Yes/No dropdown renderer
     *
     * @return Yesno
     * @throws LocalizedException
     */
    protected function getYesNoRenderer(): Yesno
    {
        if (!$this->yesNoRenderer) { // @phpstan-ignore-line
            $this->yesNoRenderer = $this->getLayout()->createBlock( // @phpstan-ignore-line
                Yesno::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->yesNoRenderer->setClass('admin__control-select'); // @phpstan-ignore-line
        }

        return $this->yesNoRenderer;
    }

    /**
     * Prepare to render
     *
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('enabled', [
            'label' => __('Enabled'),
            'renderer' => $this->getYesNoRenderer()
        ]);

        $this->addColumn('url', [
            'label' => __('Page URL'),
            'class' => 'required-entry admin__control-text'
        ]);

        $this->addColumn('event', [
            'label' => __('Event Name'),
            'class' => 'required-entry admin__control-text'
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Custom Page'); // @phpstan-ignore-line
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @return void
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $optionExtraAttr = [];

        $optionExtraAttr[
        'option_' . $this->getYesNoRenderer()->calcOptionHash($row->getData('enabled'))
        ] = 'selected="selected"';

        $row->setData('option_extra_attrs', $optionExtraAttr);
    }
}
