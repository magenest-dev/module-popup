<?php
namespace Magenest\Popup\Model\Source\Popup;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class PopupTemplate extends AbstractSource implements SourceInterface, OptionSourceInterface{
    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $templateFactory = $objectManager->create('Magenest\Popup\Model\Template');
        $collections = $templateFactory->getCollection()->getItems();
        $arr = [];
        foreach ($collections as $collection){
            $arr[$collection->getTemplateId()] =$collection->getTemplateName();
        }
        return $arr;
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