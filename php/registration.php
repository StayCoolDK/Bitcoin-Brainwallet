<?php

if(isset ($_POST['username']) && isset ($_POST['password'])) {

		//Trim the input for quotes
		$sUsername = htmlentities (trim ($_POST['username']), ENT_NOQUOTES);
		$sPassword = htmlentities (trim ($_POST['password']), ENT_NOQUOTES);

		//Hash the trimmed input
		$hPassword = password_hash($sPassword, PASSWORD_DEFAULT);

		//Verify that the password hash matches the password
		$bPassword = password_verify($sPassword, $hPassword);

		$sDate = date("Y/m/d");

		if($bPassword){
			$db = new PDO('mysql:host=localhost;dbname=WebSec01', "root", "root");
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


			$checkusername = $db->prepare("SELECT * FROM Users WHERE Username = :username");

			$checkusername->bindValue(":username", $sUsername);
			$checkusername->execute();
			$usernamecount = $checkusername->rowCount();
			
				if($usernamecount > 0) { //Username already exists

			    echo 'Username already exists';
				}
				else { //Register user

				$registeruser = $db->prepare("INSERT INTO Users (Username, Password, CreationDate, IP) VALUES (:username, :password, :currentdate, :ip)");

				$registeruser->bindValue(":username", $sUsername);
				$registeruser->bindValue(":password", $hPassword);
				$registeruser->bindValue(":currentdate", $sDate);
				$registeruser->bindValue(":ip", $_SERVER['REMOTE_ADDR']);

				$registeruser->execute();

				echo 'Registration successful';	

				$db = null;

				}

		}
}

?>