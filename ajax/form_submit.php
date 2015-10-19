<?php
session_start();

$to = 'youremail@yourdomain.com'; //Change this to your email
$success_msg = 'Thank you.'; //Form response message
$subject = 'Contact from my website'; //Email subject

send_contact($_POST);

function send_contact($args = array()){
	global $to, $success_msg, $subject;
	$error = '';
	//Check captcha first
	if(isset($_SESSION['code']) && !empty($_SESSION['code']) && strtolower($args['captcha']) != strtolower($_SESSION['code'])){
		$error = 'Wrong captcha.';
	} else {
		//Simple validation
		if(empty($args) || empty($args['name']) || empty($args['email']) || !filter_var($args['email'], FILTER_VALIDATE_EMAIL) || empty($args['message'])){
			$error = 'Please fill all the fields with correct info.';
		} else {
			extract($args);
			$message = fix_lines($message);
    	$headers = "From: $name <$email> \r\n";
    	$headers .= "Reply-To: <$email> \r\n";
    	$headers .= "Content-type: text/plain; charset=\"utf-8\"\r\n";
				mail($to, $subject, $message, $headers);
		}
	}

	$result = array();
	
	if( empty($error) ){
		$result['msg'] = $success_msg;
		$result['status'] = true;
	} else {
		$result['msg'] = $error;
		$result['status'] = false;
	}
	
	die(json_encode($result));
}


function print_log($mixed){
        if (is_array($mixed)) {
            $mixed = print_r($mixed, 1);
        } else if (is_object($mixed)) {
            ob_start();
            var_dump($mixed);
            $mixed = ob_get_clean();
        }
        
        $handle = fopen(dirname(__FILE__) . '/log', 'a');
        fwrite($handle, $mixed . PHP_EOL);
        fclose($handle);
}

function fix_lines($string) {
    return str_replace(array('\r\n', '\r', '\n'), "\n", $string);
}
?>