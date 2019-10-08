<?php
namespace Magenest\Popup\Controller\Popup;

use Magento\Framework\App\Action\Context;

class Preview extends \Magento\Framework\App\Action\Action {
    /** @var  \Magento\Framework\View\Result\PageFactory $resultPageFactory */
    protected $resultPageFactory;
    /** @var  \Magenest\Popup\Model\PopupFactory $popupFactory */
    protected $_popupFactory;

    /** @var  \Psr\Log\LoggerInterface $_logger */
    protected $_logger;

    /** @var  \Magento\Framework\Registry $_coreRegistry */
    protected $_coreRegistry;
    public function __construct(
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Action\Context $context
    ){
        $this->_popupFactory = $popupFactory;
        $this->_logger = $logger;
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $popupModel = $this->_popupFactory->create();
        try{
            $popup_id = $this->_request->getParam('popup_id');
            if($popup_id){
                $popupModel->load($popup_id);
                if(!$popupModel->getPopupId()){
                    $this->messageManager->addError(__('This Popup doesn\'t exist'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/index');
                }
            }
        }catch (\Exception $exception){
            $this->messageManager->addError($exception->getMessage());
            $this->_logger->critical($exception->getMessage());
        }
        $this->_coreRegistry->register('popup',$popupModel);
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Preview Popup'));
        return $resultPage;
    }
}