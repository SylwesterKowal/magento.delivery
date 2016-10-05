<?php

class SylwesterKowal_Delivery_Block_Adminhtml_Sales_Order_Renderer_Link extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $html = '';
        $row->setClass('a-center');
        $delivery_link = Mage::helper('adminhtml')->__('Send');
        $url = Mage::helper("adminhtml")->getUrl("admin_delivery/adminhtml_deliverybackend/send/", array("orderId" => $row->getData('increment_id')));
        $html = '<a href="' . $url . '" >' . $delivery_link . '</a>';
        return $html;
    }


    public function renderCss()
    {
        return parent::renderCss() . ' a-center';
    }
}