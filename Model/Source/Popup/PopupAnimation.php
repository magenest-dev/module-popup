<?php
namespace Magenest\Popup\Model\Source\Popup;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class PopupAnimation extends AbstractSource implements SourceInterface, OptionSourceInterface{
    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
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