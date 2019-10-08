<?php
namespace Magenest\Popup\Controller\Adminhtml\Popup;

abstract class Popup extends \Magento\Backend\App\Action{
    /** @var  \Magenest\Popup\Model\PopupFactory */
    protected $_popupFactory;
    /** @var  \Magenest\Popup\Model\TemplateFactory $_popupTemplateFactory */
    protected $_popupTemplateFactory;
    /** @var  \Psr\Log\LoggerInterface $_logger */
    protected $_logger;
    /** @var  \Magento\Framework\Registry $_coreRegistry */
    protected $_coreRegistry;

    protected $_dateTime;
    /** @var \Magento\Framework\App\Cache\TypeListInterface $cache */
    protected $cache;

    public function __construct(
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Model\TemplateFactory $popupTemplateFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\App\Cache\TypeListInterface $cache,
        \Magento\Backend\App\Action\Context $context
    ){
        $this->_popupFactory = $popupFactory;
        $this->_popupTemplateFactory = $popupTemplateFactory;
        $this->_logger = $logger;
        $this->_coreRegistry = $coreRegistry;
        $this->_dateTime = $dateTime;
        $this->cache = $cache;
        parent::__construct($context);
    }
    public function validDateFromTo($from, $to){
        if($from == '' || $to == ''){
            return false;
        }else{
            $timestampFrom = $this->_dateTime->timestamp($from);
            $timestampTo = $this->_dateTime->timestamp($to);
            if($timestampFrom > $timestampTo){
                return __('Start Date must not be later than End Date');
            }else{
                return false;
            }
        }
    }
}