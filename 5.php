<?php

require_once('t.php');
require_once "../data.php";

$trans_id = $_POST['tid'];
$output = "";
// transaction id 5, get result
if( $trans_id == 5 )
{
	$ssid = $_REQUEST['ssid'];
	
	session_name("SolvedSESSID");
	
	if( isset($_REQUEST['ssid'] ))
	{
		session_id($ssid);	
	}
	else
		send_error("ERR_SESSION_ID");
		
	session_start();
	if( !isset($_SESSION['uid']) )
	{
		session_write_close();
		send_error("ERR_SESSION_EXP");
	}
	
	$uid = $_SESSION['uid'];
	
	if($_SESSION['result'] != 0) die();
	
	if( isset($_SESSION['data']))
	{
		connect();
		
		$i = $_REQUEST['grid'];
		$index = $_REQUEST['index'];
		$angle = $_REQUEST['angle'];
		$grid = -1;
		$res = 0;
		$chal_time = false;
		$chal_points = false;

		switch($_SESSION['data']['puzzle_type']) {
			case 2:
			case 4:
			$chal_points = true;
			break;
			case 3:
			case 5:
			$chal_time = true;
			break;
		}
		
		$timer = strtotime('now') - $_SESSION['data']['timer'];

		// Out of time?
		if($chal_time && $timer >= $_SESSION['data']['puzzle_time']) {
			$timer = $_SESSION['data']['puzzle_time'];
			$_SESSION['result'] = -1;
			$res = -1;
		} else {

			if($i != $_SESSION['data']['puzzle'][$index]['index'] || isset($_SESSION['data']['puzzle'][$index]['placed'])) {
				$_SESSION['data']['errors']++;
				$_SESSION['data']['streak'] = 0;
				$_SESSION['data']['points']-=25;
				$res = 0;
			} else {
				if($_SESSION['data']['puzzle'][$index]['data'][$angle]['angle'] == 0) {
					$_SESSION['data']['puzzle_count']--;
					$_SESSION['data']['puzzle'][$index]['placed'] = 1;
					$grid = $_SESSION['data']['puzzle'][$index]['index'];
					
					$_SESSION['data']['streak']++;
					if($_SESSION['data']['streak'] >= 3)
						$_SESSION['data']['points']+=$_SESSION['data']['streak']*5;
					else
						$_SESSION['data']['points']+=5;
					
					$res = 1;
				}
				else {
					$res = 0;
					$_SESSION['data']['errors']++;
					$_SESSION['data']['streak'] = 0;
					$_SESSION['data']['points']-=25;
				}
				
			}
		}

		$pct = number_format(floor((($_SESSION['data']['puzzle_max']-$_SESSION['data']['puzzle_count'])/$_SESSION['data']['puzzle_max'])*100), 0, '','');
		$height = floor(137*($pct/100));
		$top = 193+(137-$height);

		// out of points?
		if($_SESSION['data']['points'] <= 0) {
			$_SESSION['data']['points'] = 0;
			
			if($_SESSION['data']['puzzle_type'] != 1) {
				$_SESSION['result'] = -1;
				$res = -1;
			}
		}
		
		//completed
		if($_SESSION['data']['puzzle_count'] == 0) {
			if($chal_points) { 
				if($_SESSION['data']['points'] >= $_SESSION['data']['puzzle_score']) {
					if($_SESSION['data']['errors'] == 0)
						$_SESSION['result'] = 2;
					else
						$_SESSION['result'] = 1;
						
					$_SESSION['data']['stars'] = 1;
					if($_SESSION['data']['points'] >= 600) $_SESSION['data']['stars'] = 2;
					if($_SESSION['data']['points'] >= 1000) $_SESSION['data']['stars'] = 3;
						
					UpdateRecords($_SESSION['uid'], $_SESSION['data']);
				} else {
					$_SESSION['result'] = -1;
					$res = -1;
				}
				
			} else {
				if($_SESSION['data']['errors'] == 0)
					$_SESSION['result'] = 2;
				else
					$_SESSION['result'] = 1;
					
				$_SESSION['data']['stars'] = 1;
				if($_SESSION['data']['points'] >= 600) $_SESSION['data']['stars'] = 2;
				if($_SESSION['data']['points'] >= 1000) $_SESSION['data']['stars'] = 3;
					
				UpdateRecords($_SESSION['uid'], $_SESSION['data']);
			}
		}
		
		$output = $res."|".$_SESSION['data']['puzzle_count']."|".$height."|".$top."|".$_SESSION['data']['points']."|".$_SESSION['data']['streak']."|".$grid."|".$timer."|".$_SESSION['result'];

		UpdateSession($_SESSION['uid'], $_SESSION['data']);
		
		
		if($_SESSION['result'] != 0) {
			//unset($_SESSION['data']);
			$_SESSION['data']['attempts'] = 0;
			SetUserDaily($_SESSION['uid']);
		}
		
		disconnect();
		
	}
	else
	{
		send_error("ERR_SESSION_EXP");
	}
	
	session_write_close();
	
	$data[] = array(
		'status' => 'SUCCESS',
		'output' => $output
		);
		
	echo json_encode($data);
}
else
{
	echo "ERR_WRONG_TRANS";	
}

?>