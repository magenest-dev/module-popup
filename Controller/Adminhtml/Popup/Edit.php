<?php
namespace Magenest\Popup\Controller\Adminhtml\Popup;

class Edit extends \Magenest\Popup\Controller\Adminhtml\Popup\Popup {

    /** @var  \Magento\Framework\View\Result\PageFactory $resultPageFactory */
    protected $resultPageFactory;

    public function __construct(
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Model\TemplateFactory $popupTemplateFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\App\Cache\TypeListInterface $cache,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($popupFactory, $popupTemplateFactory, $logger, $coreRegistry, $dateTime, $cache, $context);
    }

    public function execute()
    {
        $popupModel = $this->_popupFactory->create();
        try{
            $popup_id = $this->_request->getParam('id');
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
        $resultPage->getConfig()->getTitle()->prepend($popupModel->getPopupId() ? __($popupModel->getPopupName()) : __('New Popup'));
        return $resultPage;
    }
}