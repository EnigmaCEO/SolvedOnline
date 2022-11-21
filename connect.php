<?
require_once "setConnect.php";

$dbh;
$dbh2;

function connect() {
	global $dbh, $config, $dbh2;
	$dbh=new mysqli($config["dbloc"], $config["dbuser"], $config["dbpass"]) or die ('I cannot connect to the database because: ');
	$dbh->select_db($config["database"]) or die( "Unable to select database.");
	
}

function disconnect() {
	global $dbh, $dbh2;
	if($dbh) $dbh->close();
	if($dbh2) mysql_close($dbh2);
}

function query($query)
{
	global $dbh;
	
	$result = $dbh->query($query);
	if( !$result )
	{
		return false;
	}
	
	$result_array = array();
	for( $i = 0; $i < $result->num_rows; $i++ )
	{
		$result_entry = $result->fetch_assoc();
		$result_array[] = $result_entry;
	}
	
	$result->free();
	return $result_array;
}

function update($query)
{
	global $dbh;
	if( !$dbh->query($query))
	{
		//printf("Error message: %s\n", db_error());
		return false;
	}
	
	return true;
}

function get_last_id()
{
	global $dbh;
	return $dbh->insert_id;
}

function num_rows($arr)
{
	return count($arr);
}

function db_error()
{
	global $dbh;
	if($dbh) return $dbh->connect_error;
}

function db_mres($input)
{
	global $dbh;
	return mysqli_real_escape_string($dbh, $input);
}

setEnigma();

?>