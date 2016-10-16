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

class Sylwesterkowal_OrdersGrid_Model_Observer extends Varien_Event_Observer
{
		 public function salesOrderGridCollectionLoadBefore($observer)
		{
//			$_enable = Mage::getStoreConfig('ordersgrid/sylwesterkowal_ordersgrid_settings/show_admin_orders_grid');
//			if ( $_enable=='0'){} else {
//				$collection = $observer->getOrderGridCollection();
//				$select = $collection->getSelect()->distinct();
//				$select->joinLeft(array('payment'=>$collection->getTable('sales/order_payment')), 'payment.parent_id=main_table.entity_id',array('payment_method'=>'method'), null, 'left');
//				$select->joinLeft(array('address'=>$collection->getTable('sales/order_address')), 'address.parent_id=main_table.entity_id and address.address_type = "billing"', array('order_telephone' => 'telephone','order_postcode' => 'postcode'), null, 'left');
//
//			}
		}
		 
		public function appendOrdersGrid(Varien_Event_Observer $observer)
		{
			$_enable = Mage::getStoreConfig('ordersgrid/sylwesterkowal_ordersgrid_settings/show_admin_orders_grid');
			if ( $_enable=='0'){} else {
				$block = $observer->getBlock();
					if (!isset($block)) {
						return $this;
					} 
//					if ($block->getType() == 'adminhtml/sales_order_grid') {
//						$block->addColumnAfter('payment_method', array(
//							'header'    => 'Payment Method',
//							'type'  	=> 'text',
//							'align'     =>'center',
//							'index'     => 'payment_method',
//							'filter_index' => 'payment.method',
//						), 'billing_name');
//					}
//
//					if ($block->getType() == 'adminhtml/sales_order_grid') {
//					$block->addColumnAfter('order_telephone', array(
//						'header'    => 'Order Telephone',
//						'type'  	=> 'text',
//						'align'     =>'center',
//						'index'     => 'order_telephone',
//						'filter_index' => 'address.telephone',
//						), 'billing_name');
//					}
//
//					if ($block->getType() == 'adminhtml/sales_order_grid') {
//					$block->addColumnAfter('order_postcode', array(
//						'header'    => 'Postcode',
//						'type'  	=> 'text',
//						'align'     =>'center',
//						'index'     => 'order_postcode',
//						'filter_index' => 'address.postcode',
//						), 'billing_name');
//					}
			}	
		}
			
		public function KurierOrdersGrid(Varien_Event_Observer $observer)
		{
			$_enable = Mage::getStoreConfig('ordersgrid/sylwesterkowal_ordersgrid_settings/show_admin_orders_grid');
			if ( $_enable=='0'){} else {
				$block = $observer->getBlock();
					if (!isset($block)) {
						return $this;
					}
						
					if ($block->getType() == 'adminhtml/sales_order_grid') { 
						$block->addColumnAfter('delivery', array(
							'header'    => 'Details',
							'type'  	=> 'text',
							'align'     =>'center',
							'renderer'  => 'Sylwesterkowal_OrdersGrid_Block_Adminhtml_Sales_Order_Grid_Renderer_Delivery',
							'index' => 'real_order_id',
							'filter'	 => false,
							'sortable'  => false
								), 'real_order_id');
					}
			}		
		}
		
	
}

?>