<?php
namespace Magenest\Popup\Ui\Component\Listing\Column;

class TemplateType  extends \Magento\Ui\Component\Listing\Columns\Column {
    protected $_helperData;
    protected $options;
    public function __construct(
        \Magenest\Popup\Helper\Data $helperData,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        $this->_helperData = $helperData;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            $templateType = $this->_helperData->getTemplateType();
            foreach ($dataSource['data']['items'] as & $item) {
                $template_type = $item['template_type'];
                if($templateType[$template_type]){
                    $item['template_type'] = $templateType[$template_type]->getText();
                }
            }
        }
        return $dataSource;
    }
}