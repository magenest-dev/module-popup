<?php
namespace Magenest\Popup\Block\Adminhtml\Template;

use Magento\Framework\Registry;

class Edit extends \Magento\Backend\Block\Widget\Form\Container {
    /**
     * @var \Magento\Framework\Registry $_coreRegistry
     */
    protected $_coreRegistry = null;

    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ){
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Magenest_Popup';
        $this->_controller = 'adminhtml_template';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save'));

        $backUrl = $this->getUrl('*/*/index');
        $this->buttonList->update('back', 'onclick', "setLocation('{$backUrl}')");
    }
}