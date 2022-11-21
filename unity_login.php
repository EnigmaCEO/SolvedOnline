<?
if ($_SERVER['HTTP_HOST'] != "www.enigma-games.com") {
header("HTTP/1.1 301 Moved Permanently");
header("Location: https://www.enigma-games.com".$_SERVER['REQUEST_URI']);
exit; 
}
	session_name("SolvedSESSID");

	if(isset($_GET['SolvedSESSID'])) {
		session_id($_GET['SolvedSESSID']);
	}

	session_start();
	
	require_once "fb_login.php";
	require_once "connect.php";
	require_once "data.php";
	
	$my_url = "https://www.enigma-games.com/SolvedOnline/unity_login.php";
	
	if(isset($_SESSION['google_user'])) {
		unset($_SESSION['access_token']);
		session_unset();
	}
	
	if(!isset($_SESSION['access_token'])) {
		if(!isset($_REQUEST['code']) || !isset($_REQUEST['state'])) {
			Login($my_url);
		} else {
			GetUser($my_url);
		}
	} else {
		setEnigma();
		connect();
		
		$_SESSION['user'] = GetAccount($_SESSION['id']);
		if($_SESSION['user'] == null) {
			$type = 0;
			
			CreateAccount($_SESSION['id'], $type);
			if(isset($_SESSION['fb_user'])) {
				AddFacebook($_SESSION['id'], $_SESSION['fb_user']->id);
				AddToken($_SESSION['id'], $_SESSION['access_token']);
			}
		} else {
			AddToken($_SESSION['id'], $_SESSION['access_token']);
		}
		disconnect();
		echo "<head><title>Solved! Online</title></head><center><img src='images/obj_msg_perfection.png'><br><h2>You have successfully logged in! Close the browser and return to the game to continue.</h2></center>";
	}
	
	session_write_close();
?>