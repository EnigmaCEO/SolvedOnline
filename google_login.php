<?php
/*ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);*/
date_default_timezone_set('America/New_York');
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
 
require_once "connect.php";
require_once "data.php";
	
session_name("SolvedSESSID");

if(isset($_GET['SolvedSESSID'])) {
	session_id($_GET['SolvedSESSID']);
}

session_start();

if(isset($_SESSION['fb_user'])) {
	unset($_SESSION['access_token']);
	session_unset();
}

require_once "Google/autoload.php";


/************************************************
  ATTENTION: Fill in these values! Make sure
  the redirect URI is to this page, e.g:
  http://localhost:8080/user-example.php
 ************************************************/
$client_id = '1003271582040-u2get8aibadlv1s9gijsdem3poq83v7p.apps.googleusercontent.com';
$client_secret = 'qONHfwtMSWBuJ_np_fFX2g4M';
$redirect_uri = 'http://www.enigma-games.com/SolvedOnline/google_login.php';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->setScopes('https://www.googleapis.com/auth/plus.me');

/************************************************
  If we're logging out we just need to clear our
  local access token in this case
 ************************************************/
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['g_access_token']);
}

/************************************************
  If we have a code back from the OAuth 2.0 flow,
  we need to exchange that with the authenticate()
  function. We store the resultant access token
  bundle in the session, and redirect to ourself.
 ************************************************/
if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['g_access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

/************************************************
  If we have an access token, we can make
  requests, else we generate an authentication URL.
 ************************************************/
if (isset($_SESSION['g_access_token']) && $_SESSION['g_access_token']) {
  $client->setAccessToken($_SESSION['g_access_token']);
} else {
  $authUrl = $client->createAuthUrl();
  
  header("Location: $authUrl");
	?>
	<script> window.top.location='<?=$authUrl?>';</script>
	<?
}

/************************************************
  If we're signed in we can go ahead and retrieve
  the ID token, which is part of the bundle of
  data that is exchange in the authenticate step
  - we only need to do a network call if we have
  to retrieve the Google certificate to verify it,
  and that can be cached.
 ************************************************/
if ($client->getAccessToken()) {
	
  if($client->isAccessTokenExpired()) {

    $authUrl = $client->createAuthUrl();
	unset($_SESSION['g_access_token']);
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));

  }
  $_SESSION['g_access_token'] = $client->getAccessToken();
  
  $google_oauth =new Google_Service_Oauth2($client);
  
	$_SESSION['google_user'] = $google_oauth->userinfo->get();
	$_SESSION['id'] = $google_oauth->userinfo->get()->id;
	$_SESSION['uid'] = $google_oauth->userinfo->get()->id;
	$_SESSION['name'] = $google_oauth->userinfo->get()->name;
	session_write_close();
	
	setEnigma();
		connect();
		
		$_SESSION['user'] = GetAccount($_SESSION['id']);
		if($_SESSION['user'] == null) {
			$type = 1;
			
			CreateAccount($_SESSION['id'], $type);
			if(isset($_SESSION['google_user'])) {
				AddToken($_SESSION['id'], $_SESSION['g_access_token']);
			}
		} else {
			AddToken($_SESSION['id'], $_SESSION['g_access_token']);
		}
		
		disconnect();
} 

if (strpos($client_id, "googleusercontent") == false) {
  echo missingClientSecretsWarning();
  exit;
}

if (isset($google_oauth)) {
 echo "<head><title>Solved! Online</title></head><center><img src='images/obj_msg_perfection.png'><br><h2>You have successfully logged in! Close the browser and return to the game to continue.</h2></center>";

}
session_write_close();
?>