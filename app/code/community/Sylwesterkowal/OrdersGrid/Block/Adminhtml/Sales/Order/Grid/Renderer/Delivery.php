<?php
/*
 * Orders Grid Version 1.0.0.0
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * @
 * @category    Sylwesterkowal
 * @package     Sylwesterkowal_OrdersGrid
 * @author      Sylwesterkowal
 * @copyright   Copyright (c) 2014 Sylwesterkowal
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class Sylwesterkowal_OrdersGrid_Block_Adminhtml_Sales_Order_Grid_Renderer_Delivery extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
    {
		$order_no = $row->getData('increment_id');
		
//		$order = Mage::getModel('sales/order')->load($row->getEntityId ());
//		 	$items = $order->getAllItems();
//			$itemcount=count($items);
//            $name=array();
//            $sku=array();
//            $qty=array();
//            $img=array();
//
//                foreach ($items as $itemId => $item) {
//					$name[] = $item->getName();
//					$sku[]=$item->getSku();
//					$qty[]=$item->getQtyOrdered();
//					$ids[]=$item->getProductId();
//					$product = Mage::getModel('catalog/product')->load($item->getProductId());
//					$thumbnail = $product->getThumbnail();
//					$img[] = '<div id="seowebappsordersgridimage"><img src="'.Mage::getBaseUrl('media') . 'catalog/product' . $thumbnail.'" width=50px></div>';
//					$orderitems [] = '<div id="seowebappsordersgridspec">'.$img[$itemId].$name[$itemId].'<br/>SKU: '.$sku[$itemId].'<br/>QTY: '.$qty[$itemId].'</div>';
//				}
//		$orderitemsList = implode('<hr style="border-style: dotted;" />', $orderitems);
//
//		$history = $order->getAllStatusHistory();
//		$x = 1;
//		if (is_array($history)) {
//			foreach ($history as $historyId => $historys) {
//				$comment = trim($historys->getComment());
//				if (strlen($comment) > 0 && $x <= 3) {
//					if ($x > 1) { $historyInfo .= "<br /><br />"; }
//						$historyInfo .= $this->formatDate($historys->getCreatedAt(), 'medium', true) . "<br />";
//						$cleancomment= addslashes(preg_replace("/\n\r|\r\n|\n|\r/", "", $comment));
//						$cleanedcomment = preg_replace('/[^a-zA-Z0-9@.,-_\s]/', "", $cleancomment);
//						$historyInfo .= $cleanedcomment	;
//						$x++;
//					}
//			}
//		}
//
//		$orderhover = 'Hover For Details';
//		$orderhoverdetails = 'Order No: '.$order_no.'<br /><hr />'.$orderitemsList.'<div id="seowebappsordersgridhistory">'.$historyInfo.'<div/>';
//		return   '<div class="seowebappsordersgrid" title="Details">'.$orderhover.'<div class="seowebappsordersgridshow" title=" ">'.$orderhoverdetails.'</div></div>';

        $html = '';
        $row->setClass('a-center');
        $delivery_link = Mage::helper('adminhtml')->__('Kurier');
        $url = Mage::helper("adminhtml")->getUrl("ordersgrid/adminhtml_deliverybackend/create/", array("orderId" => $order_no));
        $html = '<a target="blank" href="' . $url . '" >' . $delivery_link . '</a>';
        return $html;
    }
}
?>