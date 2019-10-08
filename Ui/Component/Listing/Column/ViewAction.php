<?php
namespace Magenest\Popup\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;

class ViewAction extends \Magento\Ui\Component\Listing\Columns\Column {
    protected $_urlBuilder;
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var \Magento\Framework\Url
     */
    protected $frontendUrl;

    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Url $frontendUrl,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        $this->_urlBuilder = $urlBuilder;
        $this->frontendUrl = $frontendUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $storeId = $this->context->getFilterParam('store_id');

            foreach ($dataSource['data']['items'] as &$item) {
                $title = $this->getEscaper()->escapeHtml($item['template_name']);
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->_urlBuilder->getUrl(
                        'magenest_popup/template/edit',
                        ['id' => $item['template_id'], 'store' => $storeId]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->_urlBuilder->getUrl(
                        'magenest_popup/template/delete',
                        ['id' => $item['template_id'], 'store' => $storeId]
                    ),
                    'label' => __('Delete'),
                    'hidden' => false,
                    'confirm' => [
                        'title' => __('Delete %1', $title),
                        'message' => __('Are you sure you want to delete a %1 record?', $title)
                    ],
                ];
                $item[$this->getData('name')]['preview'] = [
                    'href' => $this->frontendUrl->getUrl(
                        'magenest_popup/template/preview',
                        ['id' => $item['template_id'], 'store' => $storeId]
                    ),
                    'label' => __('Preview'),
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