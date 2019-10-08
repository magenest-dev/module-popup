<?php
namespace Magenest\Popup\Controller\Adminhtml\Popup;

class ChangeStatus extends \Magenest\Popup\Controller\Adminhtml\Popup\Popup {
    /** @var  \Magenest\Popup\Model\ResourceModel\Popup\CollectionFactory $_popupCollectionFactory */
    protected $_popupCollectionFactory;
    /** @var  \Magento\Ui\Component\MassAction\Filter $_filer */
    protected $_filer;
    public function __construct(
        \Magenest\Popup\Model\ResourceModel\Popup\CollectionFactory $popupCollectionFactory,
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Model\TemplateFactory $popupTemplateFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\App\Cache\TypeListInterface $cache,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter
    ){
        $this->_popupCollectionFactory = $popupCollectionFactory;
        $this->_filer = $filter;
        parent::__construct($popupFactory, $popupTemplateFactory, $logger, $coreRegistry, $dateTime, $cache, $context);
    }

    public function execute()
    {
        try{
            $collection = $this->_filer->getCollection($this->_popupCollectionFactory->create());
            $count = 0;
            /** @var \Magenest\Popup\Model\Popup $item */
            foreach ($collection->getItems() as $item){
                $status = $item->getPopupStatus();
                if($status == 1){
                    $item->setPopupStatus(0);
                }else{
                    $item->setPopupStatus(1);
                }
                $item->save();
                $count++;
            }
            /* Invalidate Full Page Cache */
            $this->cache->invalidate('full_page');
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) have been changed.', $count)
            );
        }catch (\Exception $exception){
            $this->messageManager->addError($exception->getMessage());
        }
        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}