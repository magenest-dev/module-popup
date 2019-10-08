<?php
namespace Magenest\Popup\Block\Adminhtml\Popup\Edit;

class Js extends \Magento\Framework\View\Element\Template {

    protected $_template = "Magenest_Popup::popup/js.phtml";

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ){
        parent::__construct($context, $data);
    }
}