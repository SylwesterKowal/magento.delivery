<?php

class SylwesterKowal_Delivery_Adminhtml_DeliverybackendController extends Mage_Adminhtml_Controller_Action
{
    protected $orderId;
    protected $host;
    protected $order;

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title($this->__("Delivery"));
        $this->renderLayout();
    }


    public function sendAction()
    {
        $this->loadLayout();
        $this->_title($this->__("Delivery"));

        $this->orderId = $this->getRequest()->getParam('orderId');

        if (!$this->isOrderExists()) {
            Mage::getSingleton('core/session')->addError('Order incorect!');

            $this->_redirect('adminhtml/sales_order/index');
            return;
        } else {
            $this->init();
        }

        $this->_redirect('adminhtml/sales_order/index');
    }

    private function isOrderExists()
    {
        $this->order = Mage::getModel('sales/order')->loadByIncrementId($this->orderId);
        if ($this->order->getId()) {
            return true;
        } else {
            return false;
        }
    }

    private function init()
    {
        $this->host = $this->getHost();

    }

    private function getHost()
    {
        $result = parse_url(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));
        return $result['host'];
    }
}