<? 
session_name("SolvedSESSID");

if(isset($_GET['SolvedSESSID'])) {
	session_id($_GET['SolvedSESSID']);
}
	
session_start();

if($_GET['action'] == 0) $_SESSION['sound'] = 0;
if($_GET['action'] == 1) $_SESSION['sound'] = 1;

session_write_close();
?>