<?php
namespace Magenest\Popup\Block\Popup;

class Display extends \Magento\Framework\View\Element\Template{

    /** @var \Magenest\Popup\Helper\Data $_helperData */
    protected $_helperData;

    /** @var  \Magenest\Popup\Model\PopupFactory $_popupFactory */
    protected $_popupFactory;

    /** @var  \Magenest\Popup\Model\TemplateFactory $_templateFactory */
    protected $_templateFactory;
    /** @var \Magento\Cms\Model\Template\FilterProvider $_filterProvider */
    protected $_filterProvider;

    /** @var \Magento\Framework\Stdlib\CookieManagerInterface $_cookieManager */
    protected $_cookieManager;

    /** @var  \Magenest\Popup\Helper\Cookie $_helperCookie */
    protected $_helperCookie;

    /** @var \Magento\Framework\Stdlib\DateTime\DateTime $_dateTime */
    protected $_dateTime;

    public function __construct(
        \Magenest\Popup\Helper\Data $helperData,
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magenest\Popup\Model\TemplateFactory $templateFactory,
        \Magenest\Popup\Helper\Cookie $helperCookie,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ){
        $this->_helperData = $helperData;
        $this->_popupFactory = $popupFactory;
        $this->_templateFactory = $templateFactory;
        $this->_filterProvider = $filterProvider;
        $this->_cookieManager = $cookieManager;
        $this->_helperCookie = $helperCookie;
        $this->_dateTime = $dateTime;
        parent::__construct($context, $data);
    }
    public function checkPageToShow(){
        if($this->_helperData->isModuleEnable()){
            return true;
        }
        return false;
    }
    public function getDataDisplay(){
        /** @var \Magenest\Popup\Model\Popup $popup */
        $popup = $this->getPopup();
        $data = $popup->getData();
        $class = $this->getTemplateClassDefault($popup->getPopupTemplateId());
        $data['class'] = $class;
        $urlCheckCookie = $this->getUrlCheckCookie();
        $data['url_check_cookie'] = $urlCheckCookie;
        $urlClosePopup = $this->getUrlClosePopup();
        $data['url_close_popup'] = $urlClosePopup;
        $lifetime = $this->getCookieLifeTime();
        $data['lifetime'] = $lifetime;
        return json_encode($data,JSON_HEX_QUOT|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_NUMERIC_CHECK|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_FORCE_OBJECT|JSON_PRESERVE_ZERO_FRACTION|JSON_UNESCAPED_UNICODE|JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
    public function getPopup(){
        $popupIdCookies = $this->getCookie() == null ? [] : $this->getCookie();
        $fullActionName = $this->getCurrentFullActionName();
        $today = $this->_dateTime->date('Y-m-d');
        $timestamp_today = $this->_dateTime->timestamp($today);
        $popupCollections = $this->_popupFactory->create()
            ->getCollection()
            ->addFieldToFilter('popup_position',['in',['0',$fullActionName]])
            ->addFieldToFilter('popup_status',1);
        $data = [];
        foreach ($popupCollections as $popupCollection){
            $start_date = $popupCollection->getStartDate();
            $end_date = $popupCollection->getEndDate();
            if($start_date == '' && $end_date == ''){
                $data[] = $popupCollection;
            }elseif($start_date == '' && $end_date != ''){
                $end_date_timestamp = $this->_dateTime->timestamp($end_date);
                if($end_date_timestamp >= $timestamp_today){
                    $data[] = $popupCollection;
                }
            }elseif ($start_date != '' && $end_date == ''){
                $start_date_timestamp = $this->_dateTime->timestamp($start_date);
                if($start_date_timestamp <= $timestamp_today){
                    $data[] = $popupCollection;
                }
            }elseif ($start_date != '' && $end_date != ''){
                $start_date_timestamp = $this->_dateTime->timestamp($start_date);
                $end_date_timestamp = $this->_dateTime->timestamp($end_date);
                if($start_date_timestamp <= $timestamp_today && $end_date_timestamp >= $timestamp_today){
                    $data[] = $popupCollection;
                }
            }
        }
        $popupModel = '';
        if(!empty($data)){
            $min = null;
            /** @var \Magenest\Popup\Model\Popup $popup */
            foreach ($data as $popup){
                $priority = $popup->getPriority();
                foreach ($popupIdCookies as $popupIdCookie){
                    if(($popupIdCookie['popup_id'] == $popup->getPopupId()) && ($popup->getEnableCookieLifetime() == 1)){
                        $life_time = $popup->getCookieLifetime()*1000;
                        if($timestamp_today - $popupIdCookie['timestamp'] <= $life_time) continue;
                    }
                }
                $min = $min==null?$priority:$min;
                $popupModel = $min>=$priority?$popup:$popupModel;
            }
        }
        if($popupModel instanceof \Magenest\Popup\Model\Popup ){
            $html_content = $popupModel->getHtmlContent();
            $content = $this->_filterProvider->getBlockFilter()->filter($html_content);
            $content .= '<span id="copyright">Powered by Magenest</span>';
            $popupModel->setHtmlContent($content);
        }else{
            $popupModel = $this->_popupFactory->create();
        }
        return $popupModel;
    }
    public function getCurrentFullActionName(){
        return $this->getRequest()->getFullActionName();
    }
    public function isPreview(){
        $fullActionName = $this->getCurrentFullActionName();
        if(($fullActionName == "magenest_popup_popup_preview" && $this->getRequest()->getParam('popup_id') != '' )
            ||
           ($fullActionName == "magenest_popup_template_preview" && $this->getRequest()->getParam('id') != '' )
        ){
            return true;
        }else{
            return false;
        }
    }
    public function getTemplateClassDefault($templateId){
        /** @var \Magenest\Popup\Model\Template $templateModel */
        $templateModel = $this->_templateFactory->create()->load($templateId);
        if($templateModel->getTemplateId()){
            return $templateModel->getData('class');
        }else{
            return 'popup-default-1';
        }
    }
    public function getUrlCheckCookie(){
        return $this->getUrl('magenest_popup/popup/checkCookie');
    }
    public function getUrlClosePopup(){
        return $this->getUrl('magenest_popup/popup/closePopup');
    }
    public function getCookie(){
        $cookies = $this->_helperCookie->get();
        if($cookies){
            $cookieArr = json_decode($cookies,true);
            $popupIds = [];
            $i = 0;
            foreach ($cookieArr as $key => $value){
                if($key == 'view_page'){
                    $i++;
                    continue;
                }
                $popupIds[] = [
                    'popup_id' => $key,
                    'timestamp' => $value
                ];
            }
            return $popupIds;
        }else{
            return null;
        }
    }
    public function getCookieLifeTime(){
        /** @var \Magenest\Popup\Model\Popup $collection */
        $collection = $this->_popupFactory->create()
            ->getCollection()
            ->addFieldToFilter('enable_cookie_lifetime',1)
            ->setOrder('cookie_lifetime','DESC')
            ->getFirstItem();
        return $collection->getCookieLifetime() != null ? $collection->getCookieLifetime() : 86400;
    }
}