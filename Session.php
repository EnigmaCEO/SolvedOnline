<?
header("can't-be-evil: true");
ini_set('session.use_cookies', '0');
/*if ($_SERVER['HTTP_HOST'] != "www.enigma-games.com") {
header("HTTP/1.1 301 Moved Permanently");
header("Location: https://www.enigma-games.com".$_SERVER['REQUEST_URI']);
exit; 
}

ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
ini_set('display_errors', 'On');*/
	function sanitizeXSS () {
		$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
		$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
		$_REQUEST = (array)$_POST + (array)$_GET + (array)$_REQUEST;
	}

	sanitizeXSS ();

	session_name("SolvedSESSID");

	if(isset($_GET['SolvedSESSID'])) {
		session_id($_GET['SolvedSESSID']);
	}

	session_start();
	
	
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout') {
			session_unset();
			die("OK");
		}
	
	
	require_once "data.php";
	connect();
	
	
	
	if(!isset($_SESSION['id'])) {
		if(isset($_REQUEST['fb_connect'])) {
			if(!isset($_REQUEST['code']) || !isset($_REQUEST['state'])) {
				//Login();
			} else {
				//GetUser();
			}
		} else if(isset($_REQUEST['tizen_connect'])) {
			$_SESSION['id'] = $_REQUEST['tizen_connect'];
			$_SESSION['user'] = GetAccount($_SESSION['id']);
			$_SESSION['tz_user'] = 1; 
		} else if(isset($_REQUEST['android_connect'])) {
			$_SESSION['id'] = $_REQUEST['android_connect'];
			$_SESSION['user'] = GetAccount($_SESSION['id']);
			$_SESSION['droid_user'] = 1;
		}
		else if(isset($_REQUEST['wallet_connect'])) {
			$_SESSION['id'] = $_REQUEST['wallet_connect'];
			$_SESSION['user'] = GetAccount($_SESSION['id']);
			$_SESSION['wallet_user'] = 1;
			echo "OK";
		} else {
			session_write_close();
			$my_url = "https://www.enigma-games.com/SolvedOnline/?SolvedSESSID=".session_id();
			header("Location: $my_url");
			exit();
		}
	} else {
		
		$_SESSION['user'] = GetAccount($_SESSION['id']);
		
		if($_SESSION['user'] == null) {
			$type = 0;
			
			if(isset($_REQUEST['blockstack_connect'])) {
				$type = 3;
				
				$_SESSION['id'] = $_REQUEST['blockstack_connect'];
				
				CreateAccount($_SESSION['id'], $type);
				
				$_SESSION['user'] = GetAccount($_SESSION['id']);	
				$_SESSION['bs_user'] = 1; 
				echo "OK";
			} else {
		
				if(isset($_SESSION['tz_user'])) $type = 1;
				if(isset($_SESSION['droid_user'])) $type = 2;
				if(isset($_SESSION['bs_user'])) $type = 3;
				if(isset($_SESSION['wallet_user'])) $type = 4;
				
				CreateAccount($_SESSION['id'], $type);
				//echo "OK";
			}
		} else {
			if(isset($_REQUEST['wallet_connect']))
				echo "OK";
		}
	}
	
	disconnect();
	
	if(isset($_SESSION['user']['name']))
		$name = $_SESSION['user']['name'];
	else 
		$name = "";
	
	
?>