<?php
namespace Magenest\Popup\Helper;

class Cookie extends \Magento\Framework\App\Helper\AbstractHelper {
    CONST COOKIE_NAME = 'magenest_cookie_popup';
    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     */
    protected $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface $sessionManager
     */
    protected $sessionManager;
    protected $objectManager;
    protected $remoteAddressInstance;
    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Helper\Context $context
    ){
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
        $this->objectManager = $objectManager;
        $this->remoteAddressInstance = $this->objectManager->get('Magento\Framework\HTTP\PhpEnvironment\RemoteAddress');
        parent::__construct($context);
    }
    /**
     * Get data from cookie set in remote address
     */
    public function get()
    {
        return $this->cookieManager->getCookie(self::COOKIE_NAME);
    }
    /**
     * Set data to cookie in remote address
     *
     * @param [string] $value    [value of cookie]
     * @param integer  $duration [duration for cookie]
     *
     * @return void
     */
    public function set($value, $duration = 86400)
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($duration)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());

        $this->cookieManager->setPublicCookie(
            self::COOKIE_NAME,
            $value,
            $metadata
        );
    }
    /**
     * delete cookie remote address
     *
     * @return void
     */
    public function delete()
    {
        $this->cookieManager->deleteCookie(
            self::COOKIE_NAME,
            $this->cookieMetadataFactory
                ->createCookieMetadata()
                ->setPath($this->sessionManager->getCookiePath())
                ->setDomain($this->sessionManager->getCookieDomain())
        );
    }
}