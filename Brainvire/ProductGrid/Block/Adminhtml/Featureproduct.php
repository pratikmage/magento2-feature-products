<?php

namespace Brainvire\ProductGrid\Block\Adminhtml;

/**
 * Slider grid container.
 * @category Magestore
 * @package  Magestore_Bannerslider
 * @module   Bannerslider
 * @author   Magestore Developer
 */
class Featureproduct extends \Magento\Backend\Block\Widget\Grid\Container {

    /**
     * Constructor.
     */
    // protected $_template = 'Magento_Backend::featured/product.phtml';

    protected function _construct() {

        $this->_controller = 'adminhtml_featureproduct';
        $this->_blockGroup = 'Brainvire_ProductGrid';
        $this->_headerText = __('Featured Products');
        parent::_construct();
        $this->removeButton('add');
    }

    protected function _prepareLayout() {

        $this->getToolbar()->addChild(
                'store_switcher', 'Magento\Backend\Block\Store\Switcher'
        );


        $addButtonProps = [
            'id' => 'featured-products',
            'label' => __('Save Featured Products'),
            'class' => 'primary',
            'button_class' => '',
            'class_name' => 'Magento\Backend\Block\Widget\Button',
            'onclick' => 'categorySubmit("' . $this->getUrl('*/*/save') . '")',
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    protected function _toHtml() {
        $html = '
       <form id="featured_edit_form" action="' . $this->getSaveUrl() . '" method="post" enctype="multipart/form-data">
            <input name="form_key" type="hidden" value="' . $this->getFormKey() . '" />
            <div class="no-display">
            <input type="hidden" name="featured_products" id="in_featured_products" value="" />
            </div>
        </form>';
        echo $html;
        return parent::_toHtml();
    }

    public function getSaveUrl() {
        return $this->getUrl('*/*/save', array('_current' => true));
    }

}
