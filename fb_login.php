<?
require "json.php";

$app_id = "517881954960411";
$my_url = "https://www.enigma-games.com/SolvedOnline/game.php?fb_connect=1";
$APPLICATION_SECRET = "229fc2cc12f378d342633d648c9309a7";
	
function Login($url = "") {
	global $app_id,$my_url; 
	
	if($url != "") $my_url = $url;
	
	$_SESSION = array();
	$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
	
	$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
	. $app_id . "&state=" . $_SESSION['state'] . "&grant_type=client_credentials&redirect_uri=" . urlencode($my_url);
	
	header("Location: $dialog_url");
	?>
	<script> window.top.location='<?=$dialog_url?>';</script>
	<?
}
	
function GetUser($url = "") {	
	global $app_id,$my_url,$APPLICATION_SECRET;
	
	$code = $_REQUEST["code"];
	if($url != "") $my_url = $url;
	
	if($_REQUEST['state'] == $_SESSION['state']) {
		$token_url = "https://graph.facebook.com/oauth/access_token?"
		. "client_id=" . $app_id 
		. "&client_secret=" . $APPLICATION_SECRET . "&code=" . $code . "&redirect_uri=" . urlencode($my_url);

		// request access token
		//use curl and not file_get_contents()
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL, $token_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$access_token = curl_exec($ch);
		curl_close($ch);

		$graph_url = "https://graph.facebook.com/me?" . $access_token . "&fields=id,name,picture,locale,location";

		// request user data using the access token
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL, $graph_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$temp_user = curl_exec($ch);
		curl_close($ch);

		//decode the json array to get user data

		$json = new Services_JSON();

		$user = $json->decode($temp_user);

		if(isset($user->id)) {
			//store user data
			$u = $user->name;
			$id = $user->id;
			//$username = $user->username;
			
			$_SESSION['fb_user'] = $user;
			$_SESSION['id'] = $id;
			$_SESSION['uid'] = $id;
			$_SESSION['name'] = $u;
			$_SESSION['access_token'] = $access_token;
			session_write_close();
		}
		
		header("Location: $my_url");
		exit();
	} else {
		Logout();
	}
}

function Logout() {
	$my_url = "https://www.enigma-games.com/SolvedOnline";
	
	session_destroy();
	header("Location: $my_url");
	exit();
}
?>