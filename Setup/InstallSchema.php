<?php
namespace Magenest\Popup\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        //create manage popup template

        $magenest_popup_templates = $installer->getConnection()->newTable(
            $installer->getTable('magenest_popup_templates')
        )->addColumn(
            'template_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,[
                'unsigned' => true,
                'identity' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Popup Template Id'
        )->addColumn(
            'template_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,[
                'nullable' => false
            ],
            'Popup Template Name'
        )->addColumn(
            'template_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,[
                'unsigned' => true,
                'nullable' => false
            ],
            'Popup template Type'
        )->addColumn(
            'html_content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Template Popup Html Content'
        )->addColumn(
            'css_style',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Css style'
        )->addColumn(
            'class',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Css style'
        )->setComment('Table manage template popup');
        $installer->getConnection()->createTable($magenest_popup_templates);

        //create table manage popup

        $magenest_popup = $installer->getConnection()->newTable(
            $installer->getTable('magenest_popup')
        )->addColumn(
            'popup_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,[
                'unsigned' => true,
                'identity' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Popup id'
        )->addColumn(
            'popup_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,[
                'nullable' => false
            ],
            'Popup Name'
        )->addColumn(
            'popup_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,[
                'unsigned' => true,
                'nullable' => false
            ],
            'Popup type'
        )->addColumn(
            'popup_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [],
            'Popup Status'
        )->addColumn(
            'start_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Start Date'
        )->addColumn(
            'end_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'End Date'
        )->addColumn(
            'priority',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [],
            'Priority'
        )->addColumn(
            'popup_template_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Popup Template Id'
        )->addColumn(
            'popup_trigger',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Popup display trigger'
        )->addColumn(
            'number_x',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Number X'
        )->addColumn(
            'popup_position',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Position popup'
        )->addColumn(
            'popup_animation',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Popup Animation'
        )->addColumn(
            'visible_stores',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Visible In Stores'
        )->addColumn(
            'enable_cookie_lifetime',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Enable Cookie LifeTime'
        )->addColumn(
            'cookie_lifetime',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Cookie LifeTime'
        )->addColumn(
            'coupon_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Coupon Code'
        )->addColumn(
            'thankyou_message',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Thank You Message'
        )->addColumn(
            'html_content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Template Popup Html Content'
        )->addColumn(
            'css_style',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Css style'
        )->addColumn(
            'click',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Click'
        )->addColumn(
            'view',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'View'
        )->addColumn(
            'ctr',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'CTR'
        )->setComment('Table manage popup');
        $installer->getConnection()->createTable($magenest_popup);

        $installer->endSetup();
    }
}