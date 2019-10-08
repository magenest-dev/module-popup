<?php
namespace Magenest\Popup\Helper;

use Magenest\Popup\Model\Popup;
use Magenest\Popup\Model\TemplateFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
    protected $_popupTemplateFactory;

    protected $_popupFactory;
    /** @var \Magento\Backend\App\Config $backendConfig */
    protected $backendConfig;

    /** @var \Magento\Framework\ObjectManagerInterface $_objectManager */
    protected $_objectManager;

    /** @var \Magento\Store\Model\StoreManagerInterface $_storeManager */
    protected $_storeManager;
    /**  @var array */
    protected $isArea = [];
    /**
     * @var \Magento\Framework\Filesystem $_fileSystem
     */
    protected $_fileSystem;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    public function __construct(
        \Magenest\Popup\Model\TemplateFactory $popupTemplateFactory,
        \Magenest\Popup\Model\PopupFactory $popupFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Helper\Context $context
    ){
        $this->_popupTemplateFactory = $popupTemplateFactory;
        $this->_popupFactory = $popupFactory;
        $this->_objectManager = $objectManager;
        $this->_fileSystem    = $filesystem;
        $this->_directoryList = $directoryList;
        parent::__construct($context);
    }

    public function getTemplateType(){
        return [
            \Magenest\Popup\Model\Template::YESNO_BUTTON => __('Yes/No Button'),
            \Magenest\Popup\Model\Template::CONTACT_FORM => __('Contact Form'),
            \Magenest\Popup\Model\Template::SHARE_SOCIAL => __('Social Sharing'),
            \Magenest\Popup\Model\Template::SUBCRIBE => __('Subscribe Form'),
            \Magenest\Popup\Model\Template::STATIC_POPUP => __('Static Popup')
        ];
    }
    public function getPopupType(){
        return [
            \Magenest\Popup\Model\Popup::YESNO_BUTTON => __('Yes/No Button'),
            \Magenest\Popup\Model\Popup::CONTACT_FORM => __('Contact Form'),
            \Magenest\Popup\Model\Popup::SHARE_SOCIAL => __('Social Sharing'),
            \Magenest\Popup\Model\Popup::SUBCRIBE => __('Subscribe Form'),
            \Magenest\Popup\Model\Popup::STATIC_POPUP => __('Static Popup')
        ];
    }
    public function getPopupStatus(){
        return [
            \Magenest\Popup\Model\Popup::ENABLE => __('Enable'),
            \Magenest\Popup\Model\Popup::DISABLE => __('Disable')
        ];
    }
    public function getPopupTrigger(){
        return [
            \Magenest\Popup\Model\Popup::X_SECONDS_ON_PAGE => __('After customers spend X seconds on page'),
            \Magenest\Popup\Model\Popup::SCROLL_PAGE_BY_Y_PERCENT => __('After customers scroll page by X percent'),
            \Magenest\Popup\Model\Popup::VIEW_X_PAGE => __('After customers view X pages')
        ];
    }
    public function getPopupAnimation(){
        return [
            \Magenest\Popup\Model\Popup::NONE => __('None'),
            \Magenest\Popup\Model\Popup::ZOOM => __('Zoom In'),
            \Magenest\Popup\Model\Popup::ZOOMOUT => __('Zoom Out'),
            \Magenest\Popup\Model\Popup::MOVE_FROM_LEFT => __('Move From Left'),
            \Magenest\Popup\Model\Popup::MOVE_FROM_RIGHT => __('Move From Right'),
            \Magenest\Popup\Model\Popup::MOVE_FROM_TOP => __('Move From Top'),
            \Magenest\Popup\Model\Popup::MOVE_FROM_BOTTOM => __('Move From Bottom'),
        ];
    }
    public function getPopupPosition(){
        return [
            \Magenest\Popup\Model\Popup::ALLPAGE => __('All Pages'),
            \Magenest\Popup\Model\Popup::HOMEPAGE => __('Home Page'),
            \Magenest\Popup\Model\Popup::CMSPAGE => __('All CMS Pages'),
            \Magenest\Popup\Model\Popup::CATEGORY => __('All Category Pages'),
            \Magenest\Popup\Model\Popup::PRODUCT => __('All Product Pages')
        ];
    }
    public function getTemplateNameById($template_id){
        $template_name = $template_id;
        /** @var \Magenest\Popup\Model\Template $templateModel */
        $templateModel = $this->_popupTemplateFactory->create()->load($template_id);
        if($templateModel->getTemplateId()){
            $template_name = $templateModel->getTemplateName();
        }
        return $template_name;
    }
    /**
     * Is Admin Store
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isArea(\Magento\Framework\App\Area::AREA_ADMINHTML);
    }
    /**
     * @param string $area
     * @return mixed
     */
    public function isArea($area = \Magento\Framework\App\Area::AREA_FRONTEND)
    {
        if (!isset($this->isArea[$area])) {
            /** @var \Magento\Framework\App\State $state */
            $state = $this->_objectManager->get('Magento\Framework\App\State');

            try {
                $this->isArea[$area] = ($state->getAreaCode() == $area);
            } catch (\Exception $e) {
                $this->isArea[$area] = false;
            }
        }
        return $this->isArea[$area];
    }
    public function isModuleEnable(){
        return $this->getConfig('enabled');
    }
    public function getConfig($code,$storeId = null){
        $code = ($code !== '') ? '/' . $code : '';
        return $this->getConfigValue('magenest_popup/general' . $code, $storeId);
    }
    public function getConfigValue($field, $scopeValue = null, $scopeType = \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    {
        if (!$this->isArea() && is_null($scopeValue)) {
            /** @var \Magento\Backend\App\Config $backendConfig */
            if (!$this->backendConfig) {
                $this->backendConfig = $this->_objectManager->get('Magento\Backend\App\ConfigInterface');
            }
            return $this->backendConfig->getValue($field);
        }
        return $this->scopeConfig->getValue($field, $scopeType, $scopeValue);
    }
    /**
     * Get default template path
     * @param $templateId
     * @param string $type
     * @return string
     */
    public function getTemplatePath($templateId, $type = '.html')
    {
        /** Get directory of Data.php */
        $currentDir = __DIR__;

        /** Get root directory(path of magento's project folder) */
        $rootPath = $this->_directoryList->getRoot();

        $currentDirArr = explode('\\', $currentDir);
        if (count($currentDirArr) == 1) {
            $currentDirArr = explode('/', $currentDir);
        }

        $rootPathArr = explode('/', $rootPath);
        if (count($rootPathArr) == 1) {
            $rootPathArr = explode('\\', $rootPath);
        }

        $basePath = '';
        for ($i = count($rootPathArr); $i < count($currentDirArr) - 1; $i++) {
            $basePath .= $currentDirArr[$i] . '/';
        }

        $templatePath = $basePath . 'view/adminhtml/templates/popup/template/';

        return $templatePath . $templateId . $type;
    }
    /**
     * @param $relativePath
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function readFile($relativePath)
    {
        $rootDirectory = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::ROOT);

        return $rootDirectory->readFile($relativePath);
    }
    public function getTemplateDefault($templatePath){
        return $this->readFile($this->getTemplatePath($templatePath));
    }
}