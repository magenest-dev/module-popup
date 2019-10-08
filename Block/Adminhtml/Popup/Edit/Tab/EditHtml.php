<?php
namespace Magenest\Popup\Block\Adminhtml\Popup\Edit\Tab;

class EditHtml extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
    public function __construct(
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ){
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    public function _prepareForm()
    {
        /** @var \Magenest\Popup\Model\Popup $popupModel */
        $popupModel = $this->_coreRegistry->registry('popup');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('popup_');

        $fieldset = $form->addFieldset(
            'setting_fieldset',
            [
                'legend' => __('HTML Content'),
                'class' => 'fieldset-wide'
            ]
        );
        if($popupModel->getPopupId()){
            $fieldset->addField(
                'popup_id',
                'hidden',
                [
                    'name'=>'popup_id',
                    'label' => __('Id'),
                    'title' => __('Id')
                ]
            );
        }
        $widgetFilters = ['is_email_compatible' => 1];
        $wysiwygConfig = $this->_wysiwygConfig->getConfig(['widget_filters' => $widgetFilters,'add_variables' => true]);
        $fieldset->addField(
            'html_content',
            'editor',
            [
                'name' => 'html_content',
                'label' => __('HTML Content'),
                'state' => 'html',
                'required' => true,
                'value' => $popupModel->getHtmlContent(),
                'style' => 'height: 600px;',
                'config' => $wysiwygConfig
            ]
        );
        $fieldset->addField(
            'css_style',
            'textarea',
            [
                'name' => 'css_style',
                'label' => __('CSS Style'),
                'container_id' => 'field_newsletter_styles'
            ]
        );
        $form->setValues($popupModel->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
    public function getTabLabel()
    {
        return __('HTML Content');
    }
    public function getTabTitle()
    {
        return __('HTML Content');
    }
    public function canShowTab()
    {
        /** @var \Magenest\Popup\Model\Popup $popupModel */
        $popupModel = $this->_coreRegistry->registry('popup');
        if($popupModel->getPopupTemplateId()){
            return true;
        }else{
            return false;
        }
    }
    public function isHidden()
    {
        /** @var \Magenest\Popup\Model\Popup $popupModel */
        $popupModel = $this->_coreRegistry->registry('popup');
        if($popupModel->getPopupTemplateId()){
            return false;
        }else{
            return true;
        }
    }
}