<?php
namespace Magenest\Popup\Block\Adminhtml\Popup\Custom;

class PopupSelectTemplate extends \Magento\Framework\Data\Form\Element\AbstractElement {

    protected $_elements;

    /** @var  \Magento\Framework\Registry $_coreRegistry */
    protected $_coreRegistry;
    /** @var  \Magenest\Popup\Model\ResourceModel\Template\CollectionFactory */
    protected $_popupTemplateCollectionFactory;
    public function __construct(
        \Magenest\Popup\Model\ResourceModel\Template\CollectionFactory $popupTemplateCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        array $data = []
    ){
        $this->_popupTemplateCollectionFactory = $popupTemplateCollectionFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    public function getElementHtml()
    {
        $popupModel = $this->_coreRegistry->registry('popup');
        $html = '<select id="popup_template_id" class="select admin__control-select" name="popup_template_id">';
        if($popupModel->getPopupType()){
            $popupType = $popupModel->getPopupType();
            $templateCollections = $this->_popupTemplateCollectionFactory->create()->addFieldToFilter('template_type',$popupType)->getItems();
            /** @var \Magenest\Popup\Model\Template $template */
            foreach ($templateCollections as $template){
                if($template->getTemplateId() == $popupModel->getPopupTemplateId()){
                    $html .= '<option selected value="'.$template->getTemplateId().'">'.$template->getTemplateName().'</option>';
                }else{
                    $html .= '<option value="'.$template->getTemplateId().'">'.$template->getTemplateName().'</option>';
                }
            }
        }
        $html .= '</select>';
        return $html;
    }
}