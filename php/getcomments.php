<?php
	session_start();
	if(isset($_SESSION['username']) && isset($_SESSION['token'])){
		
			$db = new PDO('mysql:host=localhost;dbname=WebSec01;charset=utf8', "user", "keawebdev16");
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$comment = $db->prepare("SELECT datestamp, username, comment FROM Comments");
			$comment->execute();
		
			$count = $comment->rowCount();
		
			$aComments = array();
		
			for($i = 0; $i < $count; $i++){
				array_push($aComments, $comment->fetch(PDO::FETCH_ASSOC));
			}
		
			//encode the output to prevent XSS attacks.
			//JSON format is broken if ENT_NOQUOTES is left out.
			echo htmlspecialchars(json_encode($aComments), ENT_NOQUOTES);
	}
	else {
		echo 'You need to be logged in.';
	}
?>