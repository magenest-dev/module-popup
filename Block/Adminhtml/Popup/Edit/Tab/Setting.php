<?php
namespace Magenest\Popup\Block\Adminhtml\Popup\Edit\Tab;

class Setting extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    /** @var  \Magenest\Popup\Model\PopupFactory $_popupFactory */
    protected $_popupFactory;

    /** @var  \Magenest\Popup\Helper\Data $_helperData */
    protected $_helperData;

    /** @var \Magento\Store\Model\System\Store $_systemStore */
    protected $_systemStore;

    public function __construct(
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Helper\Data $helperData,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ){
        $this->_popupFactory = $popupFactory;
        $this->_helperData = $helperData;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('edit_form');
        $this->setTitle(__('Setting'));
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
                'legend' => __('Popup Configuration'),
                'class' => 'fieldset-wide'
            ]
        );

        $fieldset->addType('popup_template_type', '\Magenest\Popup\Block\Adminhtml\Popup\Custom\PopupSelectTemplate');

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
        $fieldset->addField(
            'popup_template_id',
            'popup_template_type',
            [
                'name' => 'popup_template_id',
                'label' => __('Popup Template'),
                'required' => true
            ]
        );
        $fieldset->addField(
            'popup_trigger',
            'select',
            [
                'name' => 'popup_trigger',
                'label' => __('Popup Trigger'),
                'required' => true,
                'values' => $this->_helperData->getPopupTrigger()
            ]
        );
        $fieldset->addField(
            'number_x',
            'text',
            [
                'name' => 'number_x',
                'label' => __('Number X'),
                'title' => __('Number X'),
                'class' => 'validate-digits validate-greater-than-zero'
            ]
        );
        $fieldset->addField(
            'popup_position',
            'select',
            [
                'name' => 'popup_position',
                'label' => __('Popup Position'),
                'required' => true,
                'values' => $this->_helperData->getPopupPosition()
            ]
        );
        $fieldset->addField(
            'popup_animation',
            'select',
            [
                'name' => 'popup_animation',
                'label' => __('Popup Animation'),
                'required' => true,
                'values' => $this->_helperData->getPopupAnimation()
            ]
        );
        if ($this->_storeManager->isSingleStoreMode()) {
            $storeId = $this->_storeManager->getStore(true)->getStoreId();
            $fieldset->addField('visible_stores', 'hidden', ['name' => 'visible_stores[]', 'value' => $storeId]);
        } else {
            $field = $fieldset->addField(
                'visible_stores',
                'multiselect',
                [
                    'name'     => 'visible_stores[]',
                    'label'    => __('Stores'),
                    'title'    => __('Stores'),
                    'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
                    'required' => true,
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        }
        $fieldset->addField(
            'enable_cookie_lifetime',
            'select',
            [
                'name' => 'enable_cookie_lifetime',
                'label' => __('Enable Cookie Lifetime'),
                'required' => true,
                'values' => [0 => __('No'), 1 => __('Yes')],
                'note' => __('Duration (in seconds) before popup appears again for the same customer')
            ]
        );
        $fieldset->addField(
            'cookie_lifetime',
            'text',
            [
                'name' => 'cookie_lifetime',
                'label' => __('Cookie Lifetime'),
                'title' => __('Cookie Lifetime'),
                'class' => 'validate-digits validate-greater-than-zero',
                'note' => __("Cookie lifetime (in seconds)"),
            ]
        );
        $fieldset->addField(
            'coupon_code',
            'text',
            [
                'name' => 'coupon_code',
                'label' => __('Coupon Code'),
                'title' => __('Coupon Code'),
            ]
        );
        $fieldset->addField(
            'thankyou_message',
            'textarea',
            [
                'name' => 'thankyou_message',
                'label' => __('Thank You Message'),
                'title' => __('Thank You Message')
            ]
        );
        if(!empty($popupModel->getVisibleStores()) && $popupModel->getVisibleStores()){
            $popupModel->setVisibleStores(explode(',',$popupModel->getVisibleStores()));
        }
        $form->setValues($popupModel->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
    public function getTabLabel()
    {
        return __('Popup Configuration');
    }
    public function getTabTitle()
    {
        return __('Popup Configuration');
    }
    public function canShowTab()
    {
        /** @var \Magenest\Popup\Model\Popup $popupModel */
        $popupModel = $this->_coreRegistry->registry('popup');
        if($popupModel->getPopupId()){
            return true;
        }else{
            return false;
        }
    }
    public function isHidden()
    {
        /** @var \Magenest\Popup\Model\Popup $popupModel */
        $popupModel = $this->_coreRegistry->registry('popup');
        if($popupModel->getPopupId()){
            return false;
        }else{
            return true;
        }
    }
}