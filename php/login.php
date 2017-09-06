<?php

			if(isset ($_POST['username']) && isset ($_POST['password'])) {

				// Variables
				$username = htmlentities (trim ($_POST['username']), ENT_NOQUOTES);
				$password = htmlentities (trim ($_POST['password']), ENT_NOQUOTES);
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);


				$db = new PDO('mysql:host=localhost;dbname=WebSec01', "root", "root");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


				// Check if login try is more than number 3
		$countLoginTries = $db->query("SELECT * FROM login_try WHERE ip = '" .$_SERVER['REMOTE_ADDR']. "' AND timestamp > (NOW()-INTERVAL 5 MINUTE)")->rowCount();
				if ($countLoginTries >= 3){
					echo 'You have tried to sign in too many times without succeding, please wait up till 5 minutes.';
				}
				else {
					//Now let's check if there's a match.
					$checklogin = $db->prepare("SELECT * FROM Users WHERE Username = :username");
					$checklogin->bindValue(":username", $username);
					$checklogin->execute();

					$row = $checklogin->fetch(PDO::FETCH_ASSOC);
					
					$fUsername = $row['Username'];
					$fPassword = $row['Password'];
					$bPassword = password_verify($password, $fPassword);

					// Add login attempt to database
					$loginTry = $db->prepare("INSERT INTO login_try (ip, username, password) VALUES (:ip, :username, :password)");
					$loginTry->bindValue(":ip", $_SERVER['REMOTE_ADDR']);
					$loginTry->bindValue(":username", $username);
					$loginTry->bindValue(":password", $hashed_password);
					$loginTry->execute();

					if($bPassword){
						echo 'Login Success';
						$db = null;
					}
					else {
						echo 'Login Fail';
						$db = null;
					}
				
			    }
										
			}
		?>
