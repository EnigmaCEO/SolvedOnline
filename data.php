<?
error_reporting(0);
 ini_set('display_errors', 'Off');
 
require_once "connect.php";

function GetAccount($id) {
	global $dbh;
	
	$query = "SELECT * FROM `users` WHERE `id`='".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	$num= mysqli_num_rows($result);

	if($num > 0) {
		$query = "UPDATE `users` SET `last_login`= convert_tz(NOW(), 'UTC', 'US/Eastern') WHERE `id`='".mysqli_real_escape_string($dbh,$id)."'";
		mysqli_query($dbh, $query);
	
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		return null;
	}
}

function CreateAccount($id, $type) {
	global $dbh;
	$query = "INSERT into `users` (id,type) VALUES ('".mysqli_real_escape_string($dbh,$id)."','".mysqli_real_escape_string($dbh,$type)."')";
	$result = mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return mysqli_error($dbh);
	}
}

function AddFacebook($id, $fb_id) {
	global $dbh;
	$query = "UPDATE `users` SET `fb_id` = '".mysqli_real_escape_string($dbh,$fb_id)."' WHERE `id` = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return mysqli_error($dbh);
	}
}

function AddName($id, $name) {
	global $dbh;
	$query = "UPDATE `users` SET `name` = '".mysqli_real_escape_string($dbh,$name)."' WHERE `id` = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return false;
	} else {
		return true;
	}
}

function Heartbeat($id) {
	global $dbh;
	$query = "UPDATE users SET last_login=convert_tz(NOW(),'UTC','US/Eastern') WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return false;
	} else {
		return true;
	}
}

function AddSession($id, $puzzle, $data) {
	global $dbh;
	$blob = serialize($data);
	
	$query = "DELETE FROM `sessions` WHERE `user_id` = '".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
	
	$query = "INSERT into `sessions` (user_id,puzzle,data, time) VALUES ('".mysqli_real_escape_string($dbh,$id)."','".mysqli_real_escape_string($dbh,$puzzle)."','".mysqli_real_escape_string($dbh,$blob)."',convert_tz(NOW(), 'UTC', 'US/Eastern'))";
	$result = mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return mysqli_error($dbh);
	}
}

function UpdateSession($id, $data) {
	global $dbh;
	$blob = serialize($data);
	
	$query = "UPDATE `sessions` SET `data` = '".mysqli_real_escape_string($dbh,$blob)."',`time` = convert_tz(NOW(), 'UTC', 'US/Eastern') WHERE `user_id` = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return mysqli_error($dbh);
	}
}

function DeleteSession($id) {
	global $dbh;
	$query = "DELETE FROM `sessions` WHERE `user_id` = '".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return mysqli_error($dbh);
	}
}

function GetPuzzleData($id) {
	global $dbh;
	$query = "SELECT data, image, pieces, puzzle_info.* FROM puzzle_data JOIN puzzle_info ON puzzle_data.puzzle = puzzle_info.puzzle WHERE puzzle_data.puzzle = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		return null;
	}
}

function UpdateRecords($id, $data) {
	global $dbh;
	$timer = strtotime('now') - $data['timer'];
	
	$query = "SELECT * FROM `records` WHERE user_id = '".mysqli_real_escape_string($dbh,$id)."' AND `puzzle` = '".$data['puzzle_id']."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if($timer < $row['time'])
			$time = $timer;
		else
			$time = $row['time'];
			
		if($data['points'] > $row['points'])
			$points = $data['points'];
		else
			$points = $row['points'];
			
		if($data['errors'] == 0)
			$perfects = $row['perfects'] + 1;
		else
			$perfects = $row['perfects'];
			
		if($data['stars'] > $row['stars'])
			$stars = $data['stars'];
		else
			$stars = $row['stars'];
		
		
		$query = "UPDATE `records` SET `points` = '".mysqli_real_escape_string($dbh,$points)."', `time` = '".mysqli_real_escape_string($dbh,$time)."', `perfects` = '".mysqli_real_escape_string($dbh,$perfects)."', `stars` = '".mysqli_real_escape_string($dbh,$stars)."', `completed` = `completed` + 1, `last_solved` = convert_tz(NOW(), 'UTC', 'US/Eastern') WHERE user_id = '".mysqli_real_escape_string($dbh,$id)."' AND `puzzle` = '".$data['puzzle_id']."'";
		mysqli_query($dbh, $query);

	} else {
		if($data['errors'] == 0)
			$perfects = 1;
		else
			$perfects = 0;
			
		$query = "INSERT INTO `records` (user_id,puzzle,points,time, perfects, stars, last_solved) VALUES ('".mysqli_real_escape_string($dbh,$id)."','".mysqli_real_escape_string($dbh,$data['puzzle_id'])."','".mysqli_real_escape_string($dbh,$data['points'])."','".mysqli_real_escape_string($dbh,$timer)."','".mysqli_real_escape_string($dbh,$perfects)."','".mysqli_real_escape_string($dbh,$data['stars'])."',convert_tz(NOW(), 'UTC', 'US/Eastern'))";
		mysqli_query($dbh, $query);
	}
	
	if (mysqli_errno($dbh)) { 
		return mysqli_error($dbh);
	}
}

function GetPuzzles($type, $pieces) {
	global $dbh;
	$query = "SELECT image, puzzle FROM puzzle_data WHERE type = '".mysqli_real_escape_string($dbh,$type)."' AND pieces = '".mysqli_real_escape_string($dbh,$pieces)."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		$ret = array();
		
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$ret[] = $row;
		}
		
		return $ret;
	} else {
		return null;
	}

}

function GetRecord($id, $puzzle) {
	global $dbh;
	$query = "SELECT * FROM `records` WHERE user_id = '".mysqli_real_escape_string($dbh,$id)."' AND `puzzle` = '".mysqli_real_escape_string($dbh,$puzzle)."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		return null;
	}
}

function GetRecords($id) {
	global $dbh;
	$query = "SELECT * FROM `records` WHERE user_id = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		return null;
	}
}

function AddToken($id, $token) {
	global $dbh;
	$query = "UPDATE `users` SET `token`= '".mysqli_real_escape_string($dbh,$token)."', ip_address= '".mysqli_real_escape_string($dbh,$_SERVER['REMOTE_ADDR'])."' WHERE `id`='".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
}

function GetDaily() {
	global $dbh;
	$query = "SELECT * FROM daily";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		$ret = array();
		
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$ret[] = $row;
		}
		
		return $ret;
	} else {
		return null;
	}
}

function SetUserDaily($id) {
	global $dbh;
	$query = "UPDATE `users` SET `daily` = '1' WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
}

function GetUserDaily($id) {
	global $dbh;
	$query = "SELECT daily from `users` WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		return null;
	}
}

function AddGold($id, $gold) {
	global $dbh;
	$query = "UPDATE `users` SET `gold` = `gold` + '".mysqli_real_escape_string($dbh,$gold)."' WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
}

function BuyGold($id, $gold) {
	global $dbh;
	$query = "UPDATE `users` SET `gold` = `gold` + '".mysqli_real_escape_string($dbh,$gold)."', `gold_bought` = `gold_bought` + '".mysqli_real_escape_string($dbh,$gold)."'  WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
}

function UseGold($id, $gold) {
	global $dbh;
	$val = GetGold($id);
	if($gold <= $val['gold']) {
		$query = "UPDATE `users` SET `gold` = `gold` - '".mysqli_real_escape_string($dbh,$gold)."' WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
		mysqli_query($dbh, $query);
		return true;
	}
	
	return false;
}

function GetGold($id) {
	global $dbh;
	$query = "SELECT gold from `users` WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		return null;
	}
}


function ReportFortumo($data) {
	global $dbh;
	$query = "INSERT into `fortumo` (id,sender, amount, cuid, payment_id, status, revenue) VALUES (NULL,'".mysqli_real_escape_string($dbh,$data['sender'])."','".mysqli_real_escape_string($dbh,$data['amount'])."','".mysqli_real_escape_string($dbh,$data['cuid'])."','".mysqli_real_escape_string($dbh,$data['payment_id'])."','".mysqli_real_escape_string($dbh,$data['status'])."','".mysqli_real_escape_string($dbh,$data['revenue'])."')";
	$result = mysqli_query($dbh, $query);
	
	if (mysqli_errno($dbh)) { 
		return mysqli_error($dbh);
	}
}

function convertArrayKeysToUtf8(array &$array) { 
    $convertedArray = array(); 
    foreach($array as $key => $value) { 
      if(!mb_check_encoding($key, 'UTF-8')) $key = utf8_encode($key); 
      if(is_array($value)) $value = convertArrayKeysToUtf8($value); 

      $convertedArray[$key] = $value; 
    } 
    return $convertedArray; 
} 

function GetBonus($id) {
	global $dbh;
	$query = "SELECT bonus from `users` WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	$result = mysqli_query($dbh, $query);
	
	$num=mysqli_num_rows($result);

	if($num > 0) {
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		return null;
	}
}

function SetBonus($id, $val) {
	global $dbh;
	$query = "UPDATE `users` SET `bonus` = '".mysqli_real_escape_string($dbh,$val)."' WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
	mysqli_query($dbh, $query);
}

function ApplyRank($id, $puzzle) {
	global $dbh;
	$ids = array("806", "812", "818", "824");
	if (in_array($puzzle, $ids)) {
		$query = "SELECT rank from `users` WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
		$result = mysqli_query($dbh, $query);
		$rank = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		if($rank[0]['rank'] == "Tourist") $val = "Traveler";
		if($rank[0]['rank'] == "Traveler") $val = "Explorer";
		if($rank[0]['rank'] == "Explorer") $val = "Navigator";
		
		$query = "UPDATE `users` SET `rank` = '".mysqli_real_escape_string($dbh,$val)."' WHERE id = '".mysqli_real_escape_string($dbh,$id)."'";
		mysqli_query($dbh, $query);
	}
}

?>