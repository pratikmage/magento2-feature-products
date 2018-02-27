<?php

namespace Brainvire\Productgrid\Block;

use \Magento\Wishlist\Helper\Data;
use \Magento\Catalog\Api\Data\ProductInterface;

class Featured extends \Magento\Catalog\Block\Product\AbstractProduct {

    protected $_productcollection;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    public function __construct(
    \Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productcollection, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, Data $wishlistHelper, \Magento\Framework\Url\Helper\Data $urlHelper, array $data = []
    ) {
        $this->_productcollection = $productcollection;
        $this->urlHelper = $urlHelper;
        $this->wishlistHelper = $wishlistHelper;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($context, $data);
    }

    public function getCollection() {

        $collection = $this->_productcollection->create();
        if ($this->getfeaturedProductIds()) {
            $featuredproductIds = $this->getfeaturedProductIds();
            $collection->addFieldToFilter('entity_id', array('in' => $featuredproductIds));
            $collection->addAttributeToFilter('status', '1');
            $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
            $collection = $this->_addProductAttributesAndPrices($collection)
                    ->setPageSize(10)
                    ->setCurPage(1);

            return $collection;
        }
    }

    public function getfeaturedProductIds() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('bv_productgrid');
        $sql = "SELECT product_id FROM " . $tableName;
        $results = $connection->fetchAll($sql);
        $productIds = array();
        foreach ($results as $result) {
            $productIds[] = $result['product_id'];
        }
        return $productIds;
    }

    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product) {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\Action\Action::PARAM_NAME_URL_ENCODED =>
                $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    

}
