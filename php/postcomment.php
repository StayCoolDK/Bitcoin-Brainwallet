<?php
session_start();

function checkCSRF(){ 
	global $sToken;
	global $sUsername;
	global $sTimestamp;
	
	if (isset($_SERVER['HTTP_REFERER'])){
		if($_SERVER['HTTP_REFERER'] != "http://localhost/webdevexam/"){
			error_log("CSRF attempted (Refferer). IP: ".$_SERVER['REMOTE_ADDR'].", Username: ".$_SESSION['username'].", Refferer: ".$_SERVER['HTTP_REFERER'].", Timestamp: ".$sTimestamp, 0);
			return false;
		}
	}
	if ($sToken !== $_SESSION['token']){
		error_log("CSRF attempted (Token). IP: ".$_SERVER['REMOTE_ADDR'].", Username: ".$_SESSION['username'].", Refferer: ".$_SERVER['HTTP_REFERER'].", Timestamp: ".$sTimestamp, 0);
		return false;
	}
	else {
		return true;
	}
}

if(isset($_SESSION['username']) && isset($_POST['comment']) && isset($_POST['token'])){

	$sUsername = $_SESSION['username'];
	$sComment = trim($_POST['comment']);
	$oDateTime = new DateTime();
	$sTimestamp = $oDateTime->format('Y-m-d H:i:s'); // MySQL datetime format
	$sToken = $_POST['token'];

	if(checkCSRF()){ 
		// use PDO to connect through the privilege restricted user account
		$db = new PDO('mysql:host=localhost;dbname=WebSec01;charset=utf8', "user", "keawebdev16");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$comment = $db->prepare("INSERT INTO Comments (datestamp, username, comment) VALUES (:currentdate, :username, :comment)");
		$comment->bindValue(":currentdate", $sTimestamp);
		$comment->bindValue(":username", $sUsername);
		$comment->bindValue(":comment", $sComment);
		$comment->execute();
		echo 'Successful';
		$db = null;
	}
	else {
		echo 'CSRF check failed.';
	}
}

else{
	echo 'You need to be logged in to comment';
}

?>