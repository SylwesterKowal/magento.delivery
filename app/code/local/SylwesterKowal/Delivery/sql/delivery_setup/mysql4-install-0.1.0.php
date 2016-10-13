<?php
$installer = $this;
$installer->startSetup();
$sql = <<<SQLTEXT

		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo

$host = parse_url(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));

Mage::getConfig()->saveConfig('deliverysection/settings/code', crc32($host), 'default', '');

$installer->endSetup();
	 