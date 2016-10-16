<?php

class Sylwesterkowal_OrdersGrid_Adminhtml_DeliverybackendController extends Mage_Adminhtml_Controller_Action
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
        $this->renderLayout();

        $this->orderId = $this->getRequest()->getParam('orderId');
//
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

    private function getOrderItems()
    {
        try {
            $items = Mage::getResourceModel('sales/order_item_collection')->setOrderFilter($this->order->getID());


            $products = [];
            $total_weight = 0;

            foreach ($items as $item) {
                $product = [];
                $_product = Mage::getModel('catalog/product')->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)->load($item->getProductId());

//				Zend_Debug::dump($item);

                $options = unserialize($item->getData('product_options'));
                $_options = '';
                if (isset($options['options']) && is_array($options['options'])) {
                    foreach ($options['options'] as $opt) {
                        $_options .= $opt['label'] . ': ' . $opt['value'] . PHP_EOL;
                    }
                }
//                $product["ID"] = $item->getProductId(); // Product ID
                $product["SK"] = $_product->getData('sku'); // Product SKU
                $product["NA"] = $_product->getData('name'); // Product Name
                $product["QT"] = (int)$item->getQtyOrdered(); // Product Quantity
//                $product["PR"] = (float) $item->getPrice(); // Product Price Netto
//                $product["PIT"] = (float) $item->getPriceInclTax(); // Product Price Brutto
//                $product["RN"] = (float) $item->getRowTotal(); // Product Total Netto
//                $product["RB"] = (float) $item->getRowTotalInclTax(); // Product Total Brutto
//                $product["VA"] = (float) $item->getTaxAmount(); // Product Vat
//                $product["TX"] = (float) $item->getTaxPercent(); // Product Tax %
//                $product["WE"] = (float) $item->getWeight(); // Product Weight
                $product["WT"] = (float)$item->getWeight() * $item->getQtyOrdered(); // Product Total Weight
                $total_weight = $total_weight + $product["WT"];
//                $product["OP"] = $_options;

                $products['items'] = $product;
                $products['total_weight'] = $total_weight;

            }
            return $products;
        } catch (Exception $e) {
            Mage::log('Błąd odczytu produktów z zamówienia ' . $e->getMessage() . '<br/>', null, $this->logFilenameErrors);
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
        $data['ID'] = $this->order->getIncrementId();
        $data['NA'] = $this->order->getShippingAddress()->getCompany() . ' ' . $this->order->getShippingAddress()->getFirstname() . ' ' . $this->order->getShippingAddress()->getLastname();
        $data['NA'] = trim($data['NA']);
        $data['NA'] = substr($data['NA'], 0, 60);
        $data['CO'] = $this->order->getShippingAddress()->getCountryId();
        $data['CI'] = $this->order->getShippingAddress()->getCity();
        $data['ZI'] = $this->order->getShippingAddress()->getPostcode();
        $data['ST'] = $this->order->getShippingAddress()->getStreetFull();
        $data['TE'] = $this->order->getShippingAddress()->getTelephone();
        $data['EM'] = $this->order->getCustomerEmail();
        $orderItems = $this->getOrderItems();
        $data['IT'] = $orderItems['items'];
        $data['VA'] = (float)$this->order->getGrandTotal();
        $data['TW'] = (float)$orderItems['total_weight'];
        $data['PE'] = $this->order->getPayment()->getMethodInstance()->getCode(); // kod płatności z zamówienia

        $data['HO'] = $this->host;
        $data['CD'] = crc32($this->host);
        $data['ME'] = $this->getActivPaymentMethods(); // lista aktywnych metod płatności
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
        $key = md5(crc32($this->host), true);
        $plaintext = serialize($this->order_data);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
        $ciphertext = $iv . $ciphertext;
        $ciphertext_base64 = base64_encode($ciphertext);

        return $ciphertext_base64;
    }

    private function getActivPaymentMethods()
    {
        $payments = Mage::getSingleton('payment/config')->getActiveMethods();

        $methods = [];
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/' . $paymentCode . '/title');
            $methods[$paymentCode] = $paymentTitle;
        }
        return $methods;

    }
}