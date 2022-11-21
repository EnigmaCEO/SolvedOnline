<? 

	error_reporting(0);
 ini_set('display_errors', 'On');
	include_once "Session.php"
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
		<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=0.7, maximum-scale=0.7, minimum-scale=0.7, user-scalable=0" />

		<link rel="icon" type="image/png" href="https://www.enigma-games.com/SolvedOnline/icon.png">
		<link href='css/font.css' rel='stylesheet' type='text/css'>
		<link href="style2.css?ver<?=time()?>" rel="stylesheet" type="text/css" />
		<script src="js/jquery-3.4.1.min.js"></script>
		
		<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
		<script src="https://unpkg.com/moralis-v1@latest/dist/moralis.min.js"></script>
		<script type="text/javascript" src="js/ui.js?ver<?=time()?>"></script>
		
		<script src="js/soundmanager2-nodebug-jsmin.js"></script>
		<script type="text/javascript" src="js/jquery.waitforimages.min.js"></script>
		<script type="text/javascript" src="js/gameplay.js?ver<?=time()?>"></script>
		
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
			
			function Logout() {
				var http;
				http = createRequestObject();
				http.open('get', 'https://www.enigma-games.com/SolvedOnline/Session.php?action=logout&SolvedSESSID=<?=$_GET['SolvedSESSID']?>', false);
				http.send(null);
				
				var res=http.responseText;
				console.log(http);
				if(res == "OK") {
					//session.signUserOut("https://www.enigma-games.com/SolvedOnline/")
					Moralis.User.logOut();
					location.href = "https://www.enigma-games.com/SolvedOnline/";
				}
				
			}
			
			const serverUrl = "https://mdgipqyiu6sx.grandmoralis.com:2053/server";
const appId = "7cotHxshgeKG1ku2bmrqK54n4Tk1JLFCmfflLsvP";
var MoralisUser = null;

const execute = async () => {
    console.log("Started")
    await Moralis.start({ serverUrl, appId }).catch((err) => {
		console.log(err)
        switch (err.code) {
            case Moralis.Error.INVALID_SESSION_TOKEN:
                Moralis.User.logOut();
                break;

        }
    });
	

	MoralisUser = await Moralis.User.current();
    console.log(MoralisUser);
	
	const testnet = 'https://mainnet.aurora.dev';
	const walletAddress = MoralisUser.get("ethAddress");
	console.log(walletAddress)

	const web3 = new Web3(testnet);
	var balance = await web3.eth.getBalance(walletAddress); //Will give value in.
	console.log(balance)
	console.log(balance/1000000000000000000)
	$("#coins_demo").html("Eth: " + balance/1000000000000000000)
}

execute().catch((err) => {
    switch (err.code) {
        case Moralis.Error.INVALID_SESSION_TOKEN:
            Moralis.User.logOut();
            break;

    }
});
		</script>
		
		
		
		<link href="css/all.css" rel="stylesheet">
		<script defer src="js/all.js"></script>
		
	</head>

	<body>
		<div id="loading">
			<div id="loading_logo"></div>
			<div id="loading_progress"></div>
			<div id="loading_progressbar"></div>
			<div id="loading_txt">Loading...</div>
		</div>
		<div id="container">
			<div id="logo"></div>
			<div id="main_boy"></div>
			<div id="main_girl"></div>
			<div id="forum"><i class="fab fa-telegram-plane"></i> TELEGRAM</div>
			<div id="help"><i class="fas fa-sign-out-alt"></i> LOGOUT</div>
			<div id="mute" <? if(isset($_SESSION['sound']) && $_SESSION['sound'] == 0) echo "style='display:none;'"; ?> ></div>
			<div id="unmute" <? if(isset($_SESSION['sound']) && $_SESSION['sound'] == 0) echo "style='display:inline;'"; ?> ></div>
			<div id="adjust"> 
				<div id="box2"></div>
				<div id="box1"></div>
				
				<div id="puzzlemaster"></div>

				<div id="leisure"></div>
				<div id="challenge"></div>
				<div id="daily"></div>
				<div id="timed"></div>
				<div id="leaderboard"></div>
				<div id="awards"></div>

				<div id="leisure_txt">Leisure</div>
				<div id="challenge_txt">Challenge</div>
				<div id="daily_txt">Daily</div>
				<div id="timed_txt">Adventure</div>
				<div id="leaderboard_txt">Leaderboard</div>
				<div id="awards_txt">Awards</div>

				<div id="avatar_frame"><img src="images/avatar.png" width="48px"></div>
				<div id="more"></div>
				<div id="coins"></div>

				<div id="news_demo">Welcome Blockchain gamers!<br>Feel free to join our Telegram and leave feedback.</div>
				<div id="rank_demo">Tourist</div>
				<div id="avatar_demo"></div>
				<div id="name_demo"></div>
				<div id="coins_demo">Eth: 0</div>
				
				<? if(!isset($_SESSION['user']['name']) || $_SESSION['user']['name'] == "") { ?>
					<div id="name_popup" class="name_items"><br>Codename Registration</div>
					<div id="name_story" class="name_items">
					<p>Welcome traveler!</p>
					<p>You are about to join a <br>passionate community of <br>puzzle solving adventurers.</p>
					<p>Enter your codename to <br>prepare your passport:</p>
					
					<input type="text" id="codename" value="<?=$name?>" autofocus></input>
					<div id="name_submit"></div>
					</div>
				<? } ?>
			</div>
			<div id="imbri"></div>
			<div id="enigma" onClick="window.open('https://www.enigma-games.com');"></div>
		</div>
		<div class='flashDiv'></div>
	</body>
	<HEAD>
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	</HEAD>
</html>

<? if(isset($_SESSION['sound']) && $_SESSION['sound'] == 0) { ?>
<script>
if (typeof soundManager != 'undefined') soundManager.mute();
var id = document.getElementById('mute');
if(id)id.style.display = 'none';
id = document.getElementById('unmute');
if(id)id.style.display = 'block';

Mute();
</script>
<? } 
	include_once "Footer.php"
?>