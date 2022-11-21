<? 
header("can't-be-evil: true");
//header("Content-Security-Policy: default-src 'self'; connect-src 'self';font-src 'self'; img-src 'self' data: https:; style-src 'self' ; script-src 'unsafe-inline' data: https:; prefetch-src 'self';sandbox allow-scripts;");
ini_set('session.use_cookies', '0');
?>
<?php
$protocol = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
if (substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.') {
    header('Location: '.$protocol.'www.'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit;
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
		
		<? 
		function sanitizeXSS () {
			$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
			$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			$_REQUEST = (array)$_POST + (array)$_GET + (array)$_REQUEST;
		}

		sanitizeXSS ();
		?>
		
		<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
		<script src="https://unpkg.com/moralis-v1@latest/dist/moralis.min.js"></script>
		
		<script>
			function createRequestObject()
			{
				var request_o; //declare the variable to hold the object.
				var browser = navigator.appName; //find the browser name
				if(browser == "Microsoft Internet Explorer"){
					/* Create the object using MSIE's method */
					request_o = new ActiveXObject("Microsoft.XMLHTTP");
				}else{
					/* Create the object using other browser's method */
					request_o = new XMLHttpRequest();
				}
				return request_o; //return the object
			}

const serverUrl = "https://mdgipqyiu6sx.grandmoralis.com:2053/server";
const appId = "7cotHxshgeKG1ku2bmrqK54n4Tk1JLFCmfflLsvP";
var MoralisUser = null;

const execute = async () => {
    console.log("Started")
    await Moralis.start({ serverUrl, appId }).catch((err) => {
        switch (err.code) {
            case Moralis.Error.INVALID_SESSION_TOKEN:
                Moralis.User.logOut();
                break;

        }
    });
	 

    // Enable web3
    //await Moralis.enableWeb3();

    MoralisUser = await Moralis.User.current();
    console.log(MoralisUser);
	
	if(MoralisUser != null) {
		startlogin(MoralisUser.get("ethAddress"), MoralisUser.get("ethAddress"))
	}
}

execute().catch((err) => {
    switch (err.code) {
        case Moralis.Error.INVALID_SESSION_TOKEN:
            Moralis.User.logOut();
            break;

    }
});
	
		 /*var session = new blockstack.UserSession()

		 if (session.isUserSignedIn()) {
		  const userData = session.loadUserData()
		  var person = new blockstack.Person(userData.profile)
		  var name = person.name()
		  if(name == "null" || name == null) name = userData.decentralizedID;
		  startlogin(userData.identityAddress, name)
		  
		 } else if (session.isSignInPending()) {
		   session.handlePendingSignIn()
		   .then(userData => {
			   var person = new blockstack.Person(userData.profile)
			   var name = person.name()
		  		if(name == "null" || name == null) name = userData.decentralizedID;
				startlogin(userData.identityAddress, name)
		   })
		 }
		 
		function BS_login() {
		   session.redirectToSignIn("https://www.enigma-games.com/SolvedOnline/index.php", "https://www.enigma-games.com/SolvedOnline/manifest.json", ["store_write"])
		   
		 }*/
		 
		 function startlogin(id, name) {
			 
			 if(name == "null" || name == null) name = "Guest";
			 var http;
			http = createRequestObject();
			http.open('get', 'https://www.enigma-games.com/SolvedOnline/Session.php?wallet_connect='+id+ '&SolvedSESSID=<?=$_GET['SolvedSESSID']?>', false);
			http.send(null);
			
			var res=http.responseText;
			console.log(http);
			
			if(res == "OK") {
				location.href = "https://www.enigma-games.com/SolvedOnline/game.php?SolvedSESSID=<?=$_GET['SolvedSESSID']?>";
			}
		 }
		 
		</script>
		
		<base href="https://www.enigma-games.com/SolvedOnline/" />
		<meta name="description" content="Solved! Online is an online community of jigsaw puzzle solving adventurers.">
		<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=0.7, maximum-scale=0.7, minimum-scale=0.7, user-scalable=0" />

		<link rel="icon" type="image/png" href="https://www.enigma-games.com/SolvedOnline/icon.png">
		<link href='css/font.css' rel='stylesheet' type='text/css'>
		<link href="splash.css?ver<?=time()?>" rel="stylesheet" type="text/css" />
		<script src="js/jquery-3.4.1.min.js"></script>
		<script src="js/jquery.mobile-1.5.0-rc1.min.js"></script>
		<script type="text/javascript" src="js/ui.js?ver<?=time()?>"></script>
	</head>
<body>
	<div id="container">
	<div id="trailer_button"><button onclick="window.location.href = 'https://youtu.be/3SaC9c3hz6M';">Watch Trailer</button></div>
	A jigsaw puzzle solving adventure!	
		<div id="metamask_connect"></div>
	</div>

	Copyright &copy; <?php echo date('Y');?>.  Enigma Games, Inc. All rights reserved.
</body>
	<HEAD>
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	</HEAD>
</html>
<!--	
<center>
<div style="background-image:url('images/splash.png');width:800px;height:480px;"></div>
<h2>An online jigsaw solving adventure!</h2>
<a href="https://play.google.com/store/apps/details?id=com.enigmagames.solved"><img src='images/PlayStoreButton.png' height="50px" hspace="10px" vspace="10px" alt="Google Play Store"></a>
<img src='images/AppStoreButton.png' height="50px" hspace="10px" vspace="10px" alt="App Store">
<a href="http://www.amazon.com/gp/product/B010MG7DNS"><img src='images/amazon-apps-store-us-black.png' height="50px" hspace="10px" vspace="10px" alt="Amazon App Store"></a>
<a href="https://appworld.blackberry.com/webstore/content/59963913"><img src='images/badge-blackberry-world.png' height="50px" hspace="10px" vspace="10px" alt="Blackberry App World"></a> 

<br/><br/>
</center>
<a href="presskit"><img src='images/backpack.png' alt="Game Press Kit"></a><strong>Press Kit</strong><br/> -->