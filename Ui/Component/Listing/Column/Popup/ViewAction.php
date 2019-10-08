<?php
namespace Magenest\Popup\Ui\Component\Listing\Column\Popup;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;

class ViewAction extends \Magento\Ui\Component\Listing\Columns\Column {
    protected $_urlBuilder;
    /**
     * @var \Magento\Framework\Url
     */
    protected $frontendUrl;
    /**
     * @var Escaper
     */
    private $escaper;
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuider,
        \Magento\Framework\Url $frontendUrl,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        $this->_urlBuilder = $urlBuider;
        $this->frontendUrl = $frontendUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])){
            $storeId = $this->context->getFilterParam('store_id');
            foreach ($dataSource['data']['items'] as &$item) {
                $title = $this->getEscaper()->escapeHtml($item['popup_name']);
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->_urlBuilder->getUrl(
                        'magenest_popup/popup/edit',
                        ['id' => $item['popup_id'], 'store' => $storeId]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['preview'] = [
                    'href' => $this->frontendUrl->getUrl(
                        'magenest_popup/popup/preview',
                        ['popup_id' => $item['popup_id'], 'store' => $storeId]
                    ),
                    'label' => __('Preview'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->_urlBuilder->getUrl(
                        'magenest_popup/popup/delete',
                        ['id' => $item['popup_id'], 'store' => $storeId]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete %1', $title),
                        'message' => __('Are you sure you want to delete a %1 record?', $title)
                    ],
                    'hidden' => false,
                ];
            }
        }
        return $dataSource;
    }
    /**
     * Get instance of escaper
     *
     * @return Escaper
     * @deprecated 101.0.7
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}