<?php
namespace Magenest\Popup\Block\Adminhtml\Popup\Edit\Tab;

class Report extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ){
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
                'legend' => __('Report'),
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
        //label
        $fieldset->addField(
            'click',
            'label',
            [
                'name' => 'click',
                'label' => __('Click(s)')
            ]
        );
        $fieldset->addField(
            'view',
            'label',
            [
                'name' => 'view',
                'label' => __('View(s)')
            ]
        );
        $fieldset->addField(
            'ctr',
            'label',
            [
                'name' => 'ctr',
                'label' => __('CTR (%)')
            ]
        );
        $form->setValues($popupModel->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return __('Report');
    }
    public function getTabTitle()
    {
        return __('Report');
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