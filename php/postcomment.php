<?php
session_start();

if(isset($_SESSION['username']) && isset($_POST['comment']) && isset($_POST['token'])){

	$sToken = $_POST['token'];

	if ($sToken !== $_SESSION['token']){
			echo 'Your attempt of CSRF has been prevented and logged.';
			error_log('CSRF attempt: '.$_SESSION['username'].$_SERVER['REMOTE_ADDR'],3,'/attempt.log');
	}
	else{
		$sUsername = $_SESSION['username'];
		//XSS:
		$sComment = htmlentities (trim($_POST['comment']), ENT_NOQUOTES);
		$oDateTime = new DateTime();
		$sTimestamp = $oDateTime->format('Y-m-d H:i:s'); // MySQL datetime format

		// use PDO to connect through the privilege restricted user account
		$db = new PDO('mysql:host=localhost;dbname=WebSec01;charset=utf8', "user", "keawebdev16");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$comment = $db->prepare("INSERT INTO Comments (datestamp, username, comment) VALUES (:currentdate, :username, :comment)");
		$comment->bindValue(":username", $sUsername);
		$comment->bindValue(":comment", $sComment);
		$comment->bindValue(":currentdate", $sTimestamp);
		$comment->execute();
		echo 'Successful';
		$db = null;
	}
}
else{
	echo 'You need to be logged in to comment';
}

?>