<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

			if(isset ($_POST['username']) && isset ($_POST['password'])) {

				// Variables
				$username = htmlentities (trim ($_POST['username']), ENT_NOQUOTES);
				$password = htmlentities (trim ($_POST['password']), ENT_NOQUOTES);
				@$hashed_password = crypt($password);
				$db = new PDO('mysql:host=localhost;dbname=WebSec01', "root", "root");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// Check if login try is more than number 3
		$countLoginTries = $db->query("SELECT * FROM login_try WHERE ip = '" .$_SERVER['REMOTE_ADDR']. "' AND timestamp > (NOW()-INTERVAL 5 MINUTE)")->rowCount();
				if ($countLoginTries >= 3)
					echo 'Error, you have tried to sign in too many times without succeding, please wait up till 5 minutes.';

				// Check if login succeeded
				else if($username == "stemann" && $password == "hej123") {
					echo 'You are now logged in.';

				// Wrong username or password
				} else {

					// Add login try to database
					$loginTry = $db->prepare("INSERT INTO login_try (ip, username, password) VALUES (:ip, :username, :password)");
					$loginTry->bindValue(":ip", $_SERVER['REMOTE_ADDR']);
					$loginTry->bindValue(":username", $username);
					$loginTry->bindValue(":password", $hashed_password);
					$loginTry->execute();

					echo 'Wrong username or password';
				}
			}
		?>
