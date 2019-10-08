<?php
namespace Magenest\Popup\Model\ResourceModel;

class Template extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
    public function _construct()
    {
        $this->_init('magenest_popup_templates','template_id');
    }
}