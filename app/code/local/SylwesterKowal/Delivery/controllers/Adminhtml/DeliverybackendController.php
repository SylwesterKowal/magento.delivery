<?php

class SylwesterKowal_Delivery_Adminhtml_DeliverybackendController extends Mage_Adminhtml_Controller_Action
{
    protected $orderId;
    protected $host;
    protected $order;
    protected $order_data;

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title($this->__("Delivery"));
        $this->renderLayout();
    }


    public function createAction()
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
            if ($this->send()) {
                Mage::getSingleton('core/session')->addSuccess('Order send do delivery');
            } else {
                Mage::getSingleton('core/session')->addError('Order not send to delivery');
            }
        }

//        $this->_redirect('adminhtml/sales_order/index');
        return;
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
        $this->order_data = $this->getData();
    }

    private function getHost()
    {
        $result = parse_url(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));
        return $result['host'];
    }

    private function getData()
    {
        $data = array();
        $data['ORDER_ID'] = $this->order->getIncrementId();
        $data['ODB_NAZWA'] = $this->order->getShippingAddress()->getCompany() . ' ' . $this->order->getShippingAddress()->getFirstname() . ' ' . $this->order->getShippingAddress()->getLastname();
        $data['ODB_NAZWA'] = trim($data['ODB_NAZWA']);
        $data['ODB_NAZWA'] = substr($data['ODB_NAZWA'], 0, 60);
        $data['ODB_KRAJ'] = $this->order->getShippingAddress()->getCountryId();
        $data['ODB_MIEJSCOWOSC'] = $this->order->getShippingAddress()->getCity();
        $data['ODB_KOD_POCZTOWY'] = $this->order->getShippingAddress()->getPostcode();
        $data['ODB_ULICA'] = $this->order->getShippingAddress()->getStreetFull();
        $data['ODB_TELEFON'] = $this->order->getShippingAddress()->getTelephone();
        $data['ODB_EMAIL'] = $this->order->getCustomerEmail();
        $data['SET_HOST'] = $this->host;
        $data['SET_CODE'] = Mage::getStoreConfig('deliverysection/settings/code');
        $data['SET_USERNAME'] = Mage::getStoreConfig('deliverysection/settings/username');
        $data['SET_PASSWORD'] = Mage::getStoreConfig('deliverysection/settings/password');
        return $data;
    }

    private function send()
    {
        $page = '/create';
        $url = 'http://delivery.21order.com' . $page;

        $data['data'] = $this->encoding();
        header("Location: {$url}?" . http_build_query($data));
    }


    private function encoding()
    {
        $code = Mage::getStoreConfig('deliverysection/settings/code');
        $key = md5($code, true);
        $plaintext = serialize($this->order_data);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
        $ciphertext = $iv . $ciphertext;
        $ciphertext_base64 = base64_encode($ciphertext);

        return $ciphertext_base64;
    }
}