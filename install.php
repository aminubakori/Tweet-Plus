<?php
	if(!isset($_SESSION)){
		ob_start();
		session_start();
	}
?>
<?php include_once("config/config.php"); ?>
<?php
	$dbconnect = sqlite_open(DB, 0666);
	/*Query to add addressbook table*/
	$query = "CREATE TABLE users (
			    id int(10) NOT NULL PRIMARY KEY,
			    oauth_uid text,
			    oauth_token text,
			    oauth_secret text,
			    username text
			)";
	$query = sqlite_exec($dbconnect,$query);
	sqlite_close($dbconnect);
	if($query) {
		echo "Done! Redirecting...";
		header("Location:".Base_Url."/home");
	}else {
		echo "Error";
	}
?>