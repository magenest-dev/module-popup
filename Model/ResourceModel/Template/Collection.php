<?php
namespace Magenest\Popup\Model\ResourceModel\Template;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
    protected $_idFieldName = 'template_id';
    public function _construct()
    {
        $this->_init('Magenest\Popup\Model\Template','Magenest\Popup\Model\ResourceModel\Template');
    }
}