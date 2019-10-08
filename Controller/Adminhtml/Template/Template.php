<?php
namespace Magenest\Popup\Controller\Adminhtml\Template;

abstract class Template extends \Magento\Backend\App\Action {
    /** @var  \Magenest\Popup\Model\PopupFactory $_popupFactory */
    protected $_popupFactory;

    /** @var  \Magenest\Popup\Model\TemplateFactory $_popupTemplateFactory */
    protected $_popupTemplateFactory;

    /** @var  \Psr\Log\LoggerInterface $_logger */
    protected $_logger;

    /** @var  \Magento\Framework\Registry $_coreRegistry */
    protected $_coreRegistry;

    /** @var \Magento\Framework\View\Result\PageFactory $_resultPageFactory */
    protected $_resultPageFactory;

    public function __construct(
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Model\TemplateFactory $popupTemplateFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        $this->_popupFactory = $popupFactory;
        $this->_popupTemplateFactory = $popupTemplateFactory;
        $this->_logger = $logger;
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function getPopupsByTemplateId($templateId){
        $flag = false;
        try{
            /** @var \Magenest\Popup\Model\Popup $collection */
            $collection = $this->_popupFactory->create()->getCollection()
                ->addFieldToFilter('popup_template_id',$templateId)
                ->getFirstItem();
            if($collection->getPopupId()){
                $flag = true;
            }
        }catch (\Exception $e){
            $this->_logger->critical($e->getMessage());
        }
        return $flag;
    }
}