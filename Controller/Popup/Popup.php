<?php
namespace Magenest\Popup\Controller\Popup;

abstract class Popup extends \Magento\Framework\App\Action\Action {

    /** @var  \Magenest\Popup\Model\PopupFactory $_popupFactory */
    protected $_popupFactory;

    /** @var  \Magenest\Popup\Helper\Cookie $_cookieHelper */
    protected $_cookieHelper;

    /** @var \Magento\Framework\Stdlib\DateTime\DateTime $_dateTime */
    protected $_dateTime;

    /** @var \Magenest\Popup\Helper\Data $_dataHelper  */
    protected $_dataHelper;

    /** @var  \Magento\Framework\View\Result\PageFactory $resultPageFactory */
    protected $resultPageFactory;

    public function __construct(
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Helper\Cookie $cookieHelper,
        \Magenest\Popup\Helper\Data $dataHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Action\Context $context
    ){
        $this->_popupFactory = $popupFactory;
        $this->_cookieHelper = $cookieHelper;
        $this->_dataHelper = $dataHelper;
        $this->resultPageFactory = $resultPageFactory;
        $this->_dateTime = $dateTime;
        parent::__construct($context);
    }
}