<?php
namespace Magenest\Popup\Controller\Popup;

class CheckCookie extends \Magenest\Popup\Controller\Popup\Popup {

    public function execute()
    {
        $out = ['message' => 'Magenest'];
        $response = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
        $response->setHeader('Content-type', 'text/plain');
        $params = $this->getRequest()->getParams();
        if(isset($params['popup_id'])&&$params['popup_id']){
            /** @var \Magenest\Popup\Model\Popup $popupModel */
            $popupModel = $this->_popupFactory->create()->load($params['popup_id']);
            $popup_click = (int)$popupModel->getClick();
            $popup_view = (int)$popupModel->getView()+1;
            $ctr = (float)($popup_click/$popup_view)*100;
            $ctr = round($ctr,2);
            $popupModel->setClick($popup_click);
            $popupModel->setView($popup_view);
            $popupModel->setCtr($ctr);
            $popupModel->save();
        }
        $data = json_encode($out);
        $response->setContents($data);
        return $response;
    }
}