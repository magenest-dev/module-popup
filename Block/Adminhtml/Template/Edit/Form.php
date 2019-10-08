<?php
namespace Magenest\Popup\Block\Adminhtml\Template\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic {
    protected $_systemStore;
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    protected $_helperData;

    public function __construct(
        \Magenest\Popup\Helper\Data $helperData,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ){
        $this->_helperData = $helperData;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->setId('edit_form');
        $this->setTitle('General');
    }
    public function _prepareForm()
    {
        $popupTemplate = $this->_coreRegistry->registry('popup_template');
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
            ],
        ]);
        $form->setUseContainer(true);
        $form->setHtmlIdPrefix('magenest_popup_template');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Popup Template'),'class'=>'general-fieldset']);
        $fieldset->addField(
            'template_id',
            'hidden',
            [
                'name'=>'template_id',
                'label' => __('Id'),
                'title' => __('Id')
            ]
        );
        $fieldset->addField(
            'template_name',
            'text',
            [
                'name'=>'template_name',
                'label' => __('Template Name'),
                'title' => __('Template Name'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'template_type',
            'select',
            [
                'name' => 'template_type',
                'label' => __('Template Type'),
                'required' => true,
                'values' => $this->_helperData->getTemplateType()
            ]
        );
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
                'value' => $popupTemplate->getHtmlContent(),
                'style' => 'height: 600px;',
                'config' => $wysiwygConfig
            ]
        );
        $fieldset->addField(
            'css_style',
            'textarea',
            [
                'name' => 'css_style',
                'label' => __('Css Styles'),
                'container_id' => 'field_newsletter_styles'
            ]
        );
        $data = $popupTemplate->getData();
        if(!empty($data)){
            $form->setValues($data);
        }
        $this->setForm($form);
        return parent::_prepareForm();
    }
}