<?php
  require_once('setConnect.php');
  require_once('connect.php');
  require_once('data.php');

  setEnigma();
  
  // check that the request comes from Fortumo server
  if(!in_array($_SERVER['REMOTE_ADDR'],
    array('79.125.125.1', '79.125.5.205', '79.125.5.95', '54.72.6.126', '54.72.6.27', '54.72.6.17', '54.72.6.23'))) {
    header("HTTP/1.0 403 Forbidden");
	
		$mail_From = "From: solved@enigma-games.com";
		$mail_To = "ceo@enigma-games.com";
		$mail_Subject = "IP Failed";
		
		$mail_Body = "";
		
		foreach ($_REQUEST as $key => $value) {
			$req .= "$key = $value\n";
		}
		foreach ($_SERVER as $key => $value) {
			$req .= "$key = $value\n";
		}
		$mail_Body .= "\n\n\n".$req;

		mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
    die("Error: Unknown IP");
  }

  // check the signature
  $secret = '8e76df9d87996c87f46b962d75733ebf'; // insert your secret between ''
  if(empty($secret) || !check_signature($_GET, $secret)) {
    header("HTTP/1.0 404 Not Found");
	
	$mail_From = "From: solved@enigma-games.com";
		$mail_To = "ceo@enigma-games.com";
		$mail_Subject = "Sig Failed";
		
		$mail_Body = "";
		
		foreach ($_REQUEST as $key => $value) {
			$req .= "$key = $value\n";
		}
		foreach ($_SERVER as $key => $value) {
			$req .= "$key = $value\n";
		}
		$mail_Body .= "\n\n\n".$req;

		mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
		
    die("Error: Invalid signature");
  }

  $sender = $_GET['sender'];//phone num.
  $amount = $_GET['amount'];//credit
  $cuid = $_GET['cuid'];//resource i.e. user
  $payment_id = $_GET['payment_id'];//unique id
  $test = $_GET['test']; // this parameter is present only when the payment is a test payment, it's value is either 'ok' or 'fail'

  //hint: find or create payment by payment_id
  //additional parameters: operator, price, user_share, country
  
  connect();
    
  if(preg_match("/failed/i", $_GET['status'])) {
    // mark payment as failed
	$mail_From = "From: solved@enigma-games.com";
		$mail_To = "ceo@enigma-games.com";
		$mail_Subject = "Gold Failed";
		
		$mail_Body = $cuid." just failed to buy gold: ";
		
		foreach ($_REQUEST as $key => $value) {
			$req .= "$key = $value\n";
		}
		foreach ($_SERVER as $key => $value) {
			$req .= "$key = $value\n";
		}
		$mail_Body .= "\n\n\n".$req;

		mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
  } else {
    // mark payment successful
	$mail_From = "From: solved@enigma-games.com";
		$mail_To = "ceo@enigma-games.com";
		$mail_Subject = "Gold Bought";
		
		$mail_Body = $cuid." just bought gold: ";
		
		foreach ($_REQUEST as $key => $value) {
			$req .= "$key = $value\n";
		}
		foreach ($_SERVER as $key => $value) {
			$req .= "$key = $value\n";
		}
		$mail_Body .= "\n\n\n".$req;

		mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
		
	BuyGold($cuid, $amount);
  }
  
  ReportFortumo($_GET);
  disconnect();

  // print out the reply
  if($test){
    echo('TEST OK');
  }
  else {
    echo('OK');
  }
 
  function check_signature($params_array, $secret) {
    ksort($params_array);

    $str = '';
    foreach ($params_array as $k=>$v) {
      if($k != 'sig') {
        $str .= "$k=$v";
      }
    }
    $str .= $secret;
    $signature = md5($str);

    return ($params_array['sig'] == $signature);
  }
?>