<?php

if(isset ($_POST['username']) && isset ($_POST['password']) && isset($_POST['email'])){

		//Trim the input for quotes
		$sUsername = htmlentities (trim ($_POST['username']), ENT_NOQUOTES);
		$sPassword = htmlentities (trim ($_POST['password']), ENT_NOQUOTES);
		$sEmail = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
		$bEmail = filter_var($sEmail, FILTER_VALIDATE_EMAIL);

		//Hash the trimmed input
		$hPassword = password_hash($sPassword, PASSWORD_DEFAULT);

		//Verify that the password hash matches the password
		$bPassword = password_verify($sPassword, $hPassword);

		$sDate = date("Y/m/d");

		if($bPassword && $bEmail) { //Password was hashed correctly and email is valid
			$db = new PDO('mysql:host=localhost;dbname=WebSec01;charset=utf8', "user", "keawebdev16");
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


			$checkusername = $db->prepare("SELECT * FROM Users WHERE Username = :username OR Email = :email");

			$checkusername->bindValue(":username", $sUsername);
			$checkusername->bindValue(":email", $sEmail);
			$checkusername->execute();
			$usernamecount = $checkusername->rowCount();

				if($usernamecount > 0) { //Username already exists

			    echo 'Username or email already exists';
			    $db = null;

				}
				else { //Register user

				$registeruser = $db->prepare("INSERT INTO Users (Username, Password, Email, CreationDate, IP) VALUES (:username, :password, :email, :currentdate, :ip)");

				$registeruser->bindValue(":username", $sUsername);
				$registeruser->bindValue(":password", $hPassword);
				$registeruser->bindValue(":email", $sEmail);
				$registeruser->bindValue(":currentdate", $sDate);
				$registeruser->bindValue(":ip", $_SERVER['REMOTE_ADDR']);

				$registeruser->execute();

				echo 'Registration successful';

				$db = null;

				}

		}
		else {
			echo 'There was an error with the email';
		}
}

?>