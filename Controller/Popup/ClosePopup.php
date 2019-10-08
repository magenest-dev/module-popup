<?php
namespace Magenest\Popup\Controller\Popup;

class ClosePopup extends \Magenest\Popup\Controller\Popup\Popup {

    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $response = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
        $response->setHeader('Content-type', 'text/plain');
        if(isset($params['popup_id'])&&$params['popup_id']&&$params['flag'] == 0){
            /** @var \Magenest\Popup\Model\Popup $popupModel */
            $popupModel = $this->_popupFactory->create()->load($params['popup_id']);
            $popup_click = (int)$popupModel->getClick()+1;
            $popup_view = (int)$popupModel->getView();
            $ctr = (float)($popup_click/$popup_view)*100;
            $popupModel->setClick($popup_click);
            $popupModel->setView($popup_view);
            $popupModel->setCtr($ctr);
            $popupModel->save();
        }
        $data = json_encode([]);
        $response->setContents($data);
        return $response;
    }
}