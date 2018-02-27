<?php

namespace Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct;

class Save extends \Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct {

    public function execute() {

        $productIds = $this->getRequest()->getParam('featured_products');
        $productidArr = explode(',', $productIds);

        if (count($productidArr)) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('bv_productgrid');
            $deletesql = "Delete FROM " . $tableName;
            $connection->query($deletesql);
            foreach ($productidArr as $productid) {
                if ($productid) {

                    $sql = "INSERT INTO " . $tableName . " (product_id) VALUES ($productid)";
                    $connection->query($sql);
                }
            }
            $this->messageManager->addSuccess(__('Featured Products save successfully.'));
        }
        $this->_redirect('*/*/');
    }

}
