<?php
namespace Magenest\Popup\Controller\Adminhtml\Template;

class Save extends \Magenest\Popup\Controller\Adminhtml\Template\Template {

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        try{
            /** @var \Magenest\Popup\Model\Template $popupTemplate */
            $popupTemplate = $this->_popupTemplateFactory->create();
            $template_name = $params['template_name'];
            $template_type = $params['template_type'];
            $html_content = $params['html_content'];
            $css_style = $params['css_style'];
            if(isset($params['template_id'])&&$params['template_id']){
                $popupTemplate->load($params['template_id']);
            }
            $popupTemplate->setTemplateName($template_name);
            $popupTemplate->setTemplateType($template_type);
            $popupTemplate->setHtmlContent($html_content);
            $popupTemplate->setCssStyle($css_style);
            $popupTemplate->save();
            $this->_redirect('*/*/index');
            $this->messageManager->addSuccess(__('The Popup Template template has been saved.'));
        }catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->_logger->critical($e->getMessage());
            $this->messageManager->addError($e->getMessage());
        }catch  (\Exception $exception){
            $this->_logger->critical($exception->getMessage());
            $this->messageManager->addError($exception->getMessage());
        }
    }
}