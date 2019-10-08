<?php
namespace Magenest\Popup\Model\Source\Popup;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class PopupTrigger extends AbstractSource implements SourceInterface, OptionSourceInterface{
    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            \Magenest\Popup\Model\Popup::X_SECONDS_ON_PAGE => __('After customers spend X seconds on page'),
            \Magenest\Popup\Model\Popup::SCROLL_PAGE_BY_Y_PERCENT => __('After customers scroll page by X percent'),
            \Magenest\Popup\Model\Popup::VIEW_X_PAGE => __('After customers view X pages'),
            \Magenest\Popup\Model\Popup::EXIT_INTENT => __('Exit Intent')
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
}