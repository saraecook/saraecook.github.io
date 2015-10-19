<?php
session_start();

$use_captcha =  false; //Change this to 'false' if you don't want to use captcha

$captcha = array();

if($use_captcha == true){
	try {
		include("../captcha/simple-php-captcha.php");
		$captcha = captcha();
	} catch(Exception $e){
		$captcha = array();
	}
}
$_SESSION['code'] = isset($captcha['code']) && !empty($captcha['code']) ? $captcha['code'] : false;

$response = array();
if(empty($captcha)){
	$response['status'] = false;
} else {
		$response['status'] = true;
		$response['image_src'] = $captcha['image_src'];
}
die(json_encode($response))
?>