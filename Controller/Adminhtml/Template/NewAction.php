<?php
namespace Magenest\Popup\Controller\Adminhtml\Template;

class NewAction extends \Magento\Backend\App\Action {
    public function execute()
    {
        $this->_forward('edit');
    }
}