<?php

namespace Brainvire\ProductGrid\Controller\Adminhtml;

abstract class Featureproduct extends \Brainvire\ProductGrid\Controller\Adminhtml\AbstractActionClass {

    const PARAM_CRUD_ID = 'product_id';

    /**
     * Check if admin has permissions to visit related pages.
     *
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Brainvire_ProductGrid::featureproduct_grid');
    }

}
