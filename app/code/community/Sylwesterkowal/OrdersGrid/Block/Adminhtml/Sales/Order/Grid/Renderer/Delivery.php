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
class Sylwesterkowal_OrdersGrid_Block_Adminhtml_Sales_Order_Grid_Renderer_Delivery extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$order_no = $row->getData('increment_id');
		
        $html = '';
        $row->setClass('a-center');
        $delivery_link = Mage::helper('adminhtml')->__('Kurier');
        $url = Mage::helper("adminhtml")->getUrl("ordersgrid/adminhtml_deliverybackend/create/", array("orderId" => $order_no));
        $html = '<a target="blank" href="' . $url . '" >' . $delivery_link . '</a>';
        return $html;
    }
}
?>