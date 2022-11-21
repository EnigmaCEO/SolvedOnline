<?php
	require_once('connect.php');
	SetEnigma();
	
    $MY_SECRET_KEY = "v4vcde0a3cedcd1641b28a";

    $trans_id = $_GET['id'];
    $dev_id = $_GET['uid'];
    $amt = $_GET['amount'];
    $currency = $_GET['currency'];
    $open_udid = $_GET['open_udid'];
    $udid = $_GET['udid'];
    $odin1 = $_GET['odin1'];
    $mac_sha1 = $_GET['mac_sha1'];
    $custom_id = $_GET['custom_id'];
    $verifier = $_GET['verifier'];
	
	

    //verify hash
    $test_string = "" . $trans_id . $dev_id . $amt . $currency . $MY_SECRET_KEY . 
        $open_udid . $udid . $odin1 . $mac_sha1 . $custom_id;
    $test_result = md5($test_string);
	
    if($test_result != $verifier) {
        echo "vc_noreward";
        die;
    }

    $user_id = $custom_id;//TODO: get your internal user id using one of the supplied identifiers
    // OpenUDID, AdColony ID, ODIN1, custom ID can be accessed via a method call in the AdColony client SDK

    //check for a valid user
    if(!$user_id) {
        echo "vc_noreward";
        die;
    }
	
	connect();
	
	$query = sprintf(
		"UPDATE users SET gold = gold + '%s' WHERE id='%s'",
		db_mres($amt), db_mres($user_id));
	update($query);
		
    //insert the new transaction
    $query = "INSERT INTO AdColony_Transactions(id, amount, name, user_id, time) ".
        "VALUES ('".db_mres($trans_id)."', '".db_mres($amt)."', '".db_mres($currency)."', '".db_mres($user_id)."', UTC_TIMESTAMP())"; 
    $result = update($query);
	
	
	disconnect();
	
    if($result) {
        
            echo "vc_success";
			
			$mail_From = "From: ceo@enigma-games.com";
			$mail_To = "support@enigma-games.com";
			$mail_Subject = "Solved! Gold Earned  (Android)";
			
			$mail_Body = $user_id." just earned ".$amt." gold!";
			foreach ($_REQUEST as $key => $value) {
				$req .= "$key = $value\n";
			}
			foreach ($_SERVER as $key => $value) {
				$req .= "$key = $value\n";
			}
			$mail_Body .= "\n\n\n".$req;

			mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
            die;
	}
	//otherwise insert failed and AdColony should retry later
	else {
		echo "$query - mysql error number: ".db_error();
		die;
	}
    
    //TODO: award the user the appropriate amount and type of currency here
    echo "vc_success";
?>