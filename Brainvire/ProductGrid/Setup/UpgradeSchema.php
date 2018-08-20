<?php

namespace Brainvire\ProductGrid\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface {

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;

        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $table = $installer->getConnection()->newTable(
                            $installer->getTable('bv_productgrid')
                    )->addColumn(
                            'id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true], 'Id')
                    ->addColumn(
                    'product_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['unsigned' => false, 'nullable' => false], 'Product Id');

            $installer->getConnection()->createTable($table);
        }
        if (version_compare($context->getVersion(), '1.1.1') < 0) {


            $installer->getConnection()->addColumn(
                    $installer->getTable('bv_productgrid'), 'store_id', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                'nullable' => false,
                'unsigned' => false,
                'comment' => 'Store Id'
                    ]
            );
        }
    }

}
