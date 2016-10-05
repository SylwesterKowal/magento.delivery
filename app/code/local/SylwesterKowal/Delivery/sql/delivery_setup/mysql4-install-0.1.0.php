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

$page = '/install';
$url = 'http://delivery.21order.com' . $page;

$key = md5('00000000000000000000000000000000000', true);
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $host, MCRYPT_MODE_CBC, $iv);
$ciphertext = $iv . $ciphertext;
$ciphertext_base64 = base64_encode($ciphertext);


$data['data'] = $ciphertext_base64;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);

$credits = unserialize($output);


Mage::getConfig()->saveConfig('deliverysection/settings/code', $credits['code'], 'default', '');
Mage::getConfig()->saveConfig('deliverysection/settings/username', $credits['username'], 'default', '');
Mage::getConfig()->saveConfig('deliverysection/settings/password', $credits['password'], 'default', '');

$installer->endSetup();
	 