<?php
namespace Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct;

class Grid extends \Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct {

    public function execute() {
    $this->getResponse()->setBody(
        $this->_view->getLayout()->createBlock('Brainvire\ProductGrid\Block\Adminhtml\Featureproduct\Grid')->toHtml()
    );
    }

}
