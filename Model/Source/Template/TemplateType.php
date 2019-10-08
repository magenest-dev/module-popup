<?php
namespace Magenest\Popup\Model\Source\Template;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class TemplateType extends AbstractSource implements SourceInterface, OptionSourceInterface{
    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            \Magenest\Popup\Model\Template::YESNO_BUTTON => __('Yes No Button'),
            \Magenest\Popup\Model\Template::CONTACT_FORM => __('Contact Form'),
            \Magenest\Popup\Model\Template::SHARE_SOCIAL => __('Share Social'),
            \Magenest\Popup\Model\Template::SUBCRIBE => __('Subscribe Form'),
            \Magenest\Popup\Model\Template::STATIC_POPUP => __('Static Popup')
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