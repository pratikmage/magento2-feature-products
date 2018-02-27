<?php

namespace Brainvire\ProductGrid\Block\Adminhtml\Featureproduct;

class Form extends \Magento\Backend\Block\Widget\Grid\Container {

    public function __construct(\Magento\Backend\Block\Widget\Context $context, array $data = array()) {
        parent::__construct($context, $data);
    }

    public function _construct() {
        parent::_construct();
        $this->removeButton('add');
    }

    protected function _addNewButton() {
        $this->addButton('save', array(
            'label' => __('Submit Products'),
            'onclick' => 'setLocation("' . $this->getUrl('*/*/save') . '")',
        ));
    }

}
