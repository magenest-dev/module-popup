<?php
namespace Magenest\Popup\Model;

class Popup extends \Magento\Framework\Model\AbstractModel{
    // popup type
    const YESNO_BUTTON = 1;
    const CONTACT_FORM = 2;
    const SHARE_SOCIAL = 3;
    const SUBCRIBE = 4;
    const STATIC_POPUP = 5;

    //popup status
    const ENABLE = 1;
    const DISABLE = 0;

    //popup trigger
    const X_SECONDS_ON_PAGE = 1;
    const SCROLL_PAGE_BY_Y_PERCENT = 2;
    const VIEW_X_PAGE = 3;
    const EXIT_INTENT = 4;

    //popup animation
    const NONE = 0;
    const ZOOM = 1;
    const ZOOMOUT = 2;
    const MOVE_FROM_LEFT = 3;
    const MOVE_FROM_RIGHT = 4;
    const MOVE_FROM_TOP = 5;
    const MOVE_FROM_BOTTOM = 6;

    //popup Position
    const ALLPAGE = 0;
    const HOMEPAGE = 'cms_index_index';
    const CMSPAGE = 'cms_page_view';
    const CATEGORY = 'catalog_category_view';
    const PRODUCT = 'catalog_product_view';

    public function _construct()
    {
        $this->_init('Magenest\Popup\Model\ResourceModel\Popup');
    }
}