<?php
session_start();

			if(isset ($_POST['username']) && isset ($_POST['password']) && isset($_POST['token'])) {

				$sPeber = 'Qls9j-3as2daLOsxd';

				$sUsername = htmlentities (trim ($_POST['username']), ENT_NOQUOTES);
				$sPassword = htmlentities (trim ($_POST['password']), ENT_NOQUOTES);
				$sToken = $_POST['token'];

				if ($sToken !== $_SESSION['token']){ //They don't match. Log the CSRF attempt.
					echo 'Your attempt of CSRF has been prevented and logged.';
				}
				else {

				// use PDO to connect through the privilege restricted user account
				$db = new PDO('mysql:host=localhost;dbname=WebSec01;charset=utf8', "user", "keawebdev16");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					// Check if login tries are more than 3
					$checkLoginTries = $db->prepare("SELECT * FROM login_try WHERE ip = :ip AND timestamp > (NOW()-INTERVAL 5 MINUTE)");
					$checkLoginTries->bindValue(":ip", $_SERVER['REMOTE_ADDR']);
					$checkLoginTries->execute();
					$countLoginTries = $checkLoginTries->rowCount();
					if($countLoginTries >= 3){
						echo 'You have tried to sign in too many times without succeding, please wait up till 5 minutes.';
						$db = null;
					}
					else {

					//Now let's check if there's a match.

					$checklogin = $db->prepare("SELECT * FROM Users WHERE Username = :username");

					$checklogin->bindValue(":username", $sUsername);
					$checklogin->execute();

					$row = $checklogin->fetch(PDO::FETCH_ASSOC);
					
					$fUsername = $row['Username'];
					$fPassword = $row['Password'];
					$bPassword = password_verify($sPassword.$sPeber, $fPassword);

					if($bPassword){
						$_SESSION['username'] = $sUsername;
						echo 'Login Success';
						$db = null;
					}
					else {
						echo 'Login Fail';
						// Add unsuccesful login attempt to database
						$loginTry = $db->prepare("INSERT INTO login_try (ip, username) VALUES (:ip, :username)");
						$loginTry->bindValue(":ip", $_SERVER['REMOTE_ADDR']);
						$loginTry->bindValue(":username", $sUsername);
						$loginTry->execute();
						$db = null;
					}
				}						
			}
		}
		?>
