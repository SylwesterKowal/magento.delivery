<?php

class SylwesterKowal_Delivery_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareColumns()
    {

        try {
            $render = new SylwesterKowal_Delivery_Block_Adminhtml_Sales_Order_Renderer_Link();
            $this->addColumn('delivery_21w', array(
                'header' => Mage::helper('sales')->__('Delivery'),
                'index' => 'order_id',
                'width' => '50px',
                'type' => 'text',
                'filter' => false,
                'sortable' => false,
                'renderer' => $render,
            ));

            $this->addColumnsOrder('customized', 'status');

        } catch (Exception $e) {
        }

        return parent::_prepareColumns();

    }
}
			