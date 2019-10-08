<?php
namespace Magenest\Popup\Block\Adminhtml\Popup\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic {
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            [
                'data' =>
                    [
                        'id' => 'edit_form',
                        'action' => $this->getData('action'),
                        'method' => 'post',
                        'enctype' => 'multipart/form-data'
                    ]
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}