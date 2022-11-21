<?
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

$dbh;
require_once "setConnect.php";

echo "Starting... ";

function connect() {
	global $dbh, $config;
	$dbh=mysql_connect ($config["dbloc"], $config["dbuser"], $config["dbpass"]) or die ('I cannot connect to the database because: ' . mysql_error() .' '. $_SERVER[HTTP_REFERER]);
	mysql_select_db ($config["database"]) or die( "Unable to select database.");
}

function disconnect() {
	global $dbh;
	mysql_close($dbh);
}


function imageflipping($image) {
        $w = imagesx($image);
        $h = imagesy($image);
        $flipped = imagecreatetruecolor($w, $h);
		
		imagealphablending($flipped, false);
		imagesavealpha($flipped, true);
		
        imagecopy($flipped, $image, 0, 0, 0, 0, $w, $h);
		
		
        return $flipped; 
}

setEnigma();

connect();

for($w = 0; $w < 1000; $w++) {
	for($y = 0; $y < 30; $y++) {
		$image = floor($w/8)."-".$y;
		if(!file_exists("images/puzzles/".$image."/piece0-0.png")) continue;
		$p = array();
		$data = array();
		$d = 0;
		echo $image." Uploading... ".(floor($w/8)*8+$y+1)."</br>";
		
		for($r = 0; $r < 20; $r++) {
			for($c = 0; $c < 20; $c++) {
				if(!file_exists("images/puzzles/".$image."/piece".$r."-".$c.".png")) break;
				
				/*$i = imagecreatefrompng("images/puzzles/".$image."/piece".$r."-".$c.".png");
				if(!$i) break;*/
				
				for($x = 0; $x < 4; $x++) {
					/*
					
					$p[$x] = imagerotate($i, $x*90, 0);
					

					$img =  imageflip($p[$x]);

					$tmpfname = tempnam(sys_get_temp_dir(), 'puzzle');

					imagepng($img, $tmpfname);
					imagedestroy($img);

					$PSize = filesize($tmpfname);
					$pic = fread(fopen($tmpfname, "r"), $PSize);
					
					$data[$d]['data'][$x]['image'] = $pic;*/
					$data[$d]['index'] = $d;
					$data[$d]['data'][$x]['angle'] = $x*90;
					
					//unlink($tmpfname);
				}
				
				$d++;
			}
		}

		$imageurl = "images/puzzles/".$image."/puzzle.png";

		$pieces = count($data);

		$data = serialize($data);
echo $image." - ".$pieces."<br>";


		$query = "REPLACE INTO puzzle_data (puzzle, data, image, pieces, type) VALUES ('".(floor($w/8)*8+$y+1)."', '".mysql_real_escape_string($data)."', '".mysql_real_escape_string($imageurl)."', '".mysql_real_escape_string($pieces)."', '2')";
		mysql_query($query);
		$query = "INSERT INTO puzzle_info (puzzle) VALUES ('".(floor($w/8)*8+$y+1)."')";
		mysql_query($query);
	}

}

disconnect();

?>