<?php
namespace Magenest\Popup\Controller\Template;

class Preview extends \Magento\Framework\App\Action\Action {

    /** @var  \Magento\Framework\View\Result\PageFactory $resultPageFactory */
    protected $resultPageFactory;

    /** @var  \Magenest\Popup\Model\TemplateFactory $_popupTemplateFactory */
    protected $_popupTemplateFactory;

    /** @var  \Psr\Log\LoggerInterface $_logger */
    protected $_logger;

    /** @var  \Magento\Framework\Registry $_coreRegistry */
    protected $_coreRegistry;

    public function __construct(
        \Magenest\Popup\Model\TemplateFactory $popupTemplateFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Action\Context $context
    ){
        $this->_popupTemplateFactory = $popupTemplateFactory;
        $this->_logger = $logger;
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $popupTemplateModel = $this->_popupTemplateFactory->create();
        try{
            $template_id = $this->_request->getParam('id');
            if($template_id){
                $popupTemplateModel->load($template_id);
                if(!$popupTemplateModel->getTemplateId()){
                    $this->messageManager->addError(__('This Popup Template doesn\'t exist'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/index');
                }
            }
        }catch (\Exception $exception){
            $this->messageManager->addError($exception->getMessage());
            $this->_logger->critical($exception->getMessage());
        }
        $this->_coreRegistry->register('popup_template',$popupTemplateModel);
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Preview Template'));
        return $resultPage;
    }
}