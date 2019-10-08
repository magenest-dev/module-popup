<?php
namespace Magenest\Popup\Model;

class Template extends \Magento\Framework\Model\AbstractModel{
    const YESNO_BUTTON = 1;
    const CONTACT_FORM = 2;
    const SHARE_SOCIAL = 3;
    const SUBCRIBE = 4;
    const STATIC_POPUP = 5;
    public function _construct()
    {
        $this->_init('Magenest\Popup\Model\ResourceModel\Template');
    }
    /**
     * Retrieve template text wrapper
     *
     * @return string
     */
    public function getHtmlContent()
    {
        if (!$this->getData('html_content') && !$this->getTemplateId()) {
            $this->setData(
                'html_content',
                __('Power by Magenest')
            );
        }

        return $this->getData('html_content');
    }
    public function insertMultiple($dataArr){
        $this->getResource()->getConnection()->insertMultiple(
            $this->getResource()->getMainTable(),
            $dataArr
        );
    }
    public function deleteMultiple($dataArr){
        $size = count($dataArr);
        if(!is_array($dataArr)&&$size == 0) return;
        $collectionIds = implode(', ', $dataArr);
        $this->getResource()->getConnection()->delete(
            $this->getResource()->getMainTable(),
            "{$this->getIdFieldName()} in ({$collectionIds})"
        );
    }
}