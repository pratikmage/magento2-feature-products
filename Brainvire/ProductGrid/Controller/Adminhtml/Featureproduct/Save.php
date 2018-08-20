<?php

namespace Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct;

class Save extends \Brainvire\ProductGrid\Controller\Adminhtml\Featureproduct {

    public function execute() {
        $productIds = $this->getRequest()->getParam('featured_products');
        $productidArr = explode(',', $productIds);
        if ($this->getRequest()->getParam('store')) {
            $storeIds = array($this->getRequest()->getParam('store'));
            $currentstoreId = $this->getRequest()->getParam('store');
        } else {
            $currentstoreId = 0;
            $storeIds = $this->getallStoreIds();
        }

        if (count($productidArr)) {
            $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('bv_productgrid');
            foreach ($storeIds as $storeId) {
                $deletesql = "Delete FROM " . $tableName . ' WHERE store_id=' . $storeId;
                $connection->query($deletesql);
                foreach ($productidArr as $productid) {
                    if ($productid) {
                        $sql = "INSERT INTO " . $tableName . " (product_id,store_id) VALUES ($productid,$storeId)";
                        $connection->query($sql);
                    }
                }
            }
            $this->messageManager->addSuccess(__('Featured Products save successfully.'));
        }
        $this->_redirect('bvadmin/featureproduct/index');
    }

    public function getallStoreIds() {

        $storeManager = $this->_objectManager->create("\Magento\Store\Model\StoreManagerInterface");
        $stores = $storeManager->getStores(true, false);
        $storeIds = array();
        foreach ($stores as $store) {
            $storeIds[] = $store->getId();
        }
        return $storeIds;
    }

}
