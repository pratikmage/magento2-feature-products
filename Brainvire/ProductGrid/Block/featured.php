<?php

namespace Brainvire\ProductGrid\Block;


use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\Filter;

class Featured extends \Magento\Catalog\Block\Product\AbstractProduct {

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
        \Magento\Catalog\Block\Product\Context $context,
        ProductRepositoryInterface $productRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Filter $filter,    
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Framework\Url\Helper\Data $urlHelper, array $data = []
    ) {
        $this->filter = $filter;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->urlHelper = $urlHelper;
        $this->wishlistHelper = $context->getWishlistHelper();
        $this->_catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($context, $data);
    }

    public function getCollection() {

        if ($this->getfeaturedProductIds()) {
           $featuredproductIds = $this->getfeaturedProductIds();
            $this->searchCriteriaBuilder->addFilter('entity_id', $featuredproductIds, 'in');
            $searchCriteria = $this->searchCriteriaBuilder->setCurrentPage(1)->setPageSize(10)->create();
            $products = $this->productRepositoryInterface->getList($searchCriteria)->getItems();
            return $products;
        }
    }

    public function getfeaturedProductIds() {
        $storeId = $this->_storeManager->getStore()->getId();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('bv_productgrid');
        $sql = "SELECT product_id FROM " . $tableName . ' WHERE store_id=' . $storeId;
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

    private function getCustomerWishlistItemsCollection() {
        $itemsCollection = $this->wishlistHelper->getWishlist()->getItemCollection();

        return $itemsCollection;
    }

    public function isInWishlist(ProductInterface $product) {
        $productId = $product->getId();

        $itemsCollection = $this->getCustomerWishlistItemsCollection();
        $itemsIds = $itemsCollection->getColumnValues('product_id');

        return in_array($productId, $itemsIds);
    }

}
