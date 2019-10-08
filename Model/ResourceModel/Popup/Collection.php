<?php
namespace Magenest\Popup\Model\ResourceModel\Popup;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
    protected $_idFieldName = 'popup_id';
    public function _construct()
    {
        $this->_init('Magenest\Popup\Model\Popup','Magenest\Popup\Model\ResourceModel\Popup');
    }
}