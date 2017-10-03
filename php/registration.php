<?php
session_start();

if(isset ($_POST['username']) && isset ($_POST['password']) && isset ($_POST['token'])){

		$sPeber = 'Qls9j-3as2daLOsxd';

		//Trim the input for quotes
		$sUsername = htmlentities (trim($_POST['username']), ENT_NOQUOTES);
		$sPassword = htmlentities(trim($_POST['password']), ENT_NOQUOTES); 
		$sToken = $_POST['token'];

		//Hash the trimmed input
		$hPassword = password_hash($sPassword.$sPeber, PASSWORD_DEFAULT);
		$bPassword = password_verify($sPassword.$sPeber, $hPassword);

		$now = new DateTime();
		$sDate = $now->format('Y-m-d H:i:s');  // MySQL datetime format

		//Check if the token from the session matches the one which was sent to the server from the form:
		if ($sToken !== $_SESSION['token']){
			echo 'Your attempt of CSRF has been prevented and logged.';
		}
		else { 

			if($bPassword) { //Password was hashed correctly

				//Connect with PDO to a user account with limited priviliges
				$db = new PDO('mysql:host=localhost;dbname=WebSec01;charset=utf8', "user", "keawebdev16");
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


				//Use prepared statements against SQL injection.
				$checkusername = $db->prepare("SELECT * FROM Users WHERE Username = :username");
				$checkusername->bindValue(":username", $sUsername);
				$checkusername->execute();
				$usernamecount = $checkusername->rowCount();

					if($usernamecount > 0) { //Username already exists

				    echo 'Username already exists';
				    $db = null;

					}
					else { //Register user

					$registeruser = $db->prepare("INSERT INTO Users (Username, Password, CreationDate) VALUES (:username, :password, :currentdate)");

					$registeruser->bindValue(":username", $sUsername);
					$registeruser->bindValue(":password", $hPassword);
					$registeruser->bindValue(":currentdate", $sDate);
					$registeruser->execute();

					echo 'Registration successful';

					$db = null;

					}

			}
			else {
				echo 'There was an error verifying the password.';
			}

		}
}

?>