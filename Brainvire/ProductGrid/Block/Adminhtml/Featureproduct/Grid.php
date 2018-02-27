<?php

namespace Brainvire\ProductGrid\Block\Adminhtml\Featureproduct;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

    protected $_productFactory;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Template\Context                         $context
     * @param \Magento\Backend\Helper\Data                                    $backendHelper    
     * @param array                                                           $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Magento\Catalog\Model\ProductFactory $productFactory, array $data = []
    ) {

        $this->_productFactory = $productFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * [_construct description].
     *
     * @return [type] [description]
     */
    protected function _construct() {
        parent::_construct();
        $this->setId('featureproductGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setRowClickCallback('FeaturedRowClick');
    }

    /**
     * prepare collection.
     *
     * @return [type] [description]
     */
    protected function _prepareCollection() {

        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
                '*'
        );
        $this->setCollection($collection);


        return parent::_prepareCollection();
    }

    protected function _addColumnFilterToCollection($column) {
        if ($column->getId() == "in_products") {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
            }
        } else {

            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareColumns() {
        $this->addColumn('in_products', [
            'type' => 'checkbox',
            'html_name' => 'products_id',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id',
            'header_css_class' => 'col-select',
            'column_css_class' => 'col-select'
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'index' => 'name',
            'header_css_class' => 'col-name',
            'column_css_class' => 'col-name'
                ]
        );
        $this->addColumn('sku', [
            'header' => __('Sku'),
            'index' => 'sku',
            'header_css_class' => 'col-name',
            'column_css_class' => 'col-name'
                ]
        );




        return parent::_prepareColumns();
    }

    protected function _getSelectedProducts() {
        $products = $this->getSelectedProducts();

        return $products;
    }

    public function getSelectedProducts() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('bv_productgrid');
        $sql = "SELECT product_id FROM " . $tableName;
        $results = $connection->fetchAll($sql);
        $selectedProducts = array();
        foreach ($results as $productIds) {
            $selectedProducts[] = $productIds['product_id'];
        }
        return $selectedProducts;
    }

    /**
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('*/*/index', array('_current' => true));
    }

    protected function _afterToHtml($html) {
        return $this->_prependHtml() . parent::_afterToHtml($html) . $this->_appendHtml();
    }

    private function _prependHtml() {
        $gridName = $this->getJsObjectName();
        $html = <<<EndHTML
		<script type="text/javascript">              
    //<![CDATA[
        requirejs([        
        'jquery',
        'mage/template',        
        ], function ($,mageTemplate) {                
             $('#featureproductGrid_table thead label').first().css("display", "none");
            categoryForm = $('featured_edit_form');            
            
            categorySubmit=function(url) {                 
                document.forms["featured_edit_form"].submit();                              
            }
            
   
            initCheckboxes = function() {                
                var everycheckbox = $("#featureproductGrid_table tbody input.checkbox:checked");
                
                var checkBoxes = new Array();
                everycheckbox.each(function(element, index) {
                    checkBoxes.push(index.value);               
                });
                $("#in_featured_products").val(checkBoxes.toString());	                 
                }  
                
            var checkBoxes = new Array();
                
                 var checkBoxes = new Array();
                FeaturedRowClick = function(grid, event){
                selections = $.map($("#featureproductGrid_table tbody input.checkbox:checked"), function(a) {                                         
                    return a.value;                                              
                })
                render_selections();
                    
                }
                var selections = [],
                render_selections = function(){
                     $('#in_featured_products').val(selections.join(', '));
                }                                             
                
       });                
      //]]>		
        </script>          
       
EndHTML;

        return $html;
    }

    private function _appendHtml() {
        $html = <<<EndHTML
		'<script type="text/javascript">    
                    var checkBoxes;
    //<![CDATA[
        requirejs([        
        'jquery',
        'mage/template',        
        ], function ($,mageTemplate) {                                        
		
		checkbox_all = $("#featureproductGrid_table thead input.checkbox").first();               
				
	initCheckboxes();
             
                
       });                
      //]]>		
        </script>          
       
EndHTML;

        return $html;
    }

}
