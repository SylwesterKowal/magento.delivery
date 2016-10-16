<?php
/*
 * Magento Delivery 1.0.0.0
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Apache License (2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the GitHub at this URL:
 * https://github.com/SylwesterKowal/magento.delivery
 * @
 * @category    Sylwesterkowal
 * @package     Sylwesterkowal_OrdersGrid
 * @author      Sylwesterkowal
 * @copyright   Copyright (c) 2014 Sylwesterkowal
 * @license     https://github.com/SylwesterKowal/magento.delivery/blob/master/LICENSE  Apache License (2.0)
*/

class SylwesterKowal_OrdersGrid_IndexController extends Mage_Core_Controller_Front_Action
{
    private $orderId;
    protected $host;
    protected $order;
    private $shipping_carrier_code;
    private $shipping_carrier_title;
    private $tracking_number;

    public function IndexAction()
    {
        $this->parseDataParcel($this->decode());
        if (!$this->isOrderExists()) {
            Mage::getSingleton('core/session')->addError('Order incorect!');
            return;
        } else {
            if ($this->saveTrack()) {
                Mage::getSingleton('core/session')->addSuccess('Order send do delivery');
            } else {
                Mage::getSingleton('core/session')->addError('Order not send to delivery');
            }
        }
    }

    private function parseDataParcel($dataTrack)
    {
        $this->orderId = $dataTrack['orderId'];
        $this->shipping_carrier_code = $dataTrack['code'];
        $this->shipping_carrier_title = $dataTrack['title'];
        $this->tracking_number = $dataTrack['number'];
    }

    private function saveTrack()
    {
        $itemsarray = [];
        foreach ($this->order->getAllItems() as $item) {
            $item_id = $item->getItemId(); //order_item_id
            $qty = $item->getQtyOrdered();   //qty ordered for that item
            $itemsarray[$item_id] = $qty;
        }

        if ($this->order->canShip()) {
            $shipmentId = Mage::getModel('sales/order_shipment_api')->create($this->order->getIncrementId(), $itemsarray, 'Paczka nadana', false, 1);
            //echo $shipmentId;   // Outputs Shipment Increment Number
            $trackmodel = Mage::getModel('sales/order_shipment_api')
                ->addTrack($shipmentId, 'custom', $this->shipping_carrier_title, $this->tracking_number);
            return true;
        } else {
            return false;
        }
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

    private function decode($data)
    {
        try {
            $this->host = $this->getHost();
            $data = $this->getRequest()->getParam('data');
            $key = md5(crc32($this->host), true);
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $ciphertext_dec = base64_decode($data);
            $iv_dec = substr($ciphertext_dec, 0, $iv_size);
            $ciphertext_dec = substr($ciphertext_dec, $iv_size);
            $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
            $data_order = @unserialize($plaintext_dec);

            return $data_order;
        } catch (Exception $e) {
            return false;
        }
    }

    private function getHost()
    {
        $result = parse_url(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));
        return $result['host'];
    }
}