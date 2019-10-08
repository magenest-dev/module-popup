<?php
namespace Magenest\Popup\Controller\Adminhtml\Template;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action {
    /**
     * @var \Magento\Framework\View\Result\PageFactory $_resultPageFactory,
     */
    protected $_resultPageFactory;
    /**
     * @var \Magento\Framework\Registry $_coreRegistry
     */
    protected $_coreRegistry;
    /**
     * @var \Psr\Log\LoggerInterface $_logger
     */
    protected $_logger;
    /**
     * @var \Magenest\Popup\Model\TemplateFactory $_popupTemplatesFactory
     */
    protected $_popupTemplatesFactory;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        \Magenest\Popup\Model\TemplateFactory $popupTemplatesFactory,
        \Magento\Backend\App\Action\Context $context
    ){
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_logger = $logger;
        $this->_popupTemplatesFactory = $popupTemplatesFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $popupTemplate = $this->_popupTemplatesFactory->create();
        try{
            $template_id = $this->_request->getParam('id');
            if($template_id){
                $popupTemplate->load($template_id);
                if(!$popupTemplate->getTemplateId()){
                    $this->messageManager->addError(__('This Popup Template doesn\'t exist'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/index');
                }
            }
        }catch (\Exception $exception){
            $this->messageManager->addError($exception->getMessage());
            $this->_logger->critical($exception->getMessage());
        }
        $this->_coreRegistry->register('popup_template',$popupTemplate);
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend($popupTemplate->getTemplateId() ? __('Edit Popup Template') : __('New Popup Template'));
        return $resultPage;
    }
}