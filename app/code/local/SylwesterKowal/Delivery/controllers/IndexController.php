<?php
class SylwesterKowal_Delivery_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("TrackParcel"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("trackparcel", array(
                "label" => $this->__("TrackParcel"),
                "title" => $this->__("TrackParcel")
		   ));

      $this->renderLayout(); 
	  
    }
}