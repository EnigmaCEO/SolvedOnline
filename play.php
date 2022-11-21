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
	
	if(isset($_SESSION['id'])) {
		$my_url = "https://www.enigma-games.com/SolvedOnline/game.php";
		header("Location: $my_url");
		exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Solved! Online - A jigsaw puzzle solving adventure!</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
		<META HTTP-EQUIV="Expires" CONTENT="-1">
		<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
		
		<base href="https://www.enigma-games.com/SolvedOnline/" />
		<meta name="description" content="Solved! Online is an online community of jigsaw puzzle solving adventurers.">
		<meta name="viewport" content="width=800, target-densityDpi=device-dpi, initial-scale=0.8, maximum-scale=0.8, minimum-scale=0.8, user-scalable=0" />

		
		<link rel="icon" type="image/png" href="https://www.enigma-games.com/SolvedOnline/icon.png">
		<link href="splash.css" rel="stylesheet" type="text/css" />
		<link href='https://fonts.googleapis.com/css?family=Concert+One' rel='stylesheet' type='text/css'>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.waitforimages.min.js"></script>
		<script type="text/javascript" src="js/splash.js"></script>
		
	</head>

	<body>
		<div id="loading">
			<div id="loading_logo"></div>
			<div id="loading_progress"></div>
			<div id="loading_progressbar"></div>
			<div id="loading_txt">Loading...</div>
		</div>
		<div id="container">
		A jigsaw puzzle solving adventure!	
			<div id="fb_connect"></div>
			<div id="tizen_connect">Play Now!</div>
			<div id="imbri"></div>
			<div id="enigma" onClick="window.open('https://www.enigma-games.com');"></div>
		</div>
	</body>
	<HEAD>
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	</HEAD>
</html>