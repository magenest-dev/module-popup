<?php
namespace Magenest\Popup\Block\Adminhtml\Popup\Edit\Tab;

use Magenest\Popup\Helper\Data;
use Magenest\Popup\Model\PopupFactory;

class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
    /** @var  \Magenest\Popup\Model\PopupFactory $_popupFactory */
    protected $_popupFactory;
    /** @var  \Magenest\Popup\Helper\Data $_helperData */
    protected $_helperData;
    public function __construct(
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Helper\Data $helperData,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ){
        $this->_popupFactory = $popupFactory;
        $this->_helperData = $helperData;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    public function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        /** @var \Magenest\Popup\Model\Popup $popupModel */
        $popupModel = $this->_coreRegistry->registry('popup');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('popup_');
        $fieldset = $form->addFieldset(
            'setting_fieldset',
            [
                'legend' => __('General Setting'),
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
        $fieldset->addField(
            'popup_name',
            'text',
            [
                'name'=>'popup_name',
                'label' => __('Popup Name'),
                'title' => __('Popup Name'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'popup_status',
            'select',
            [
                'name' => 'popup_status',
                'label' => __('Popup Status'),
                'title' => __('Popup Status'),
                'required' => true,
                'values' => $this->_helperData->getPopupStatus()
            ]
        );
        $fieldset->addField(
            'popup_type',
            'select',
            [
                'name' => 'popup_type',
                'label' => __('Popup Type'),
                'title' => __('Popup Type'),
                'required' => true,
                'values' => $this->_helperData->getPopupType()
            ]
        );
        $fieldset->addField(
            'start_date',
            'date',
            [
                'name' => 'start_date',
                'date_format' => $dateFormat,
                'label' => __('Start Date'),
                'title' => __('Start Date')
            ]
        );
        $fieldset->addField(
            'end_date',
            'date',
            [
                'name' => 'end_date',
                'date_format' => $dateFormat,
                'label' => __('End Date'),
                'title' => __('Priority')
            ]
        );
        $fieldset->addField(
        'priority',
            'text',
            [
                'name' => 'priority',
                'label' => __('Priority'),
                'title' => __('Priority')
            ]
        );
        $form->setValues($popupModel->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
    public function getTabLabel()
    {
        return __('General Setting');
    }
    public function getTabTitle()
    {
        return __('General Setting');
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
}