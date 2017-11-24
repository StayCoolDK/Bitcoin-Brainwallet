<?php
session_start();

if(isset ($_POST['username']) && isset ($_POST['password']) && isset ($_POST['password2']) && isset ($_POST['token'])){
	

	$now = new DateTime();
	$sDate = $now->format('Y-m-d H:i:s');  // MySQL datetime format
	$sPeber = 'Qls9j-3as2daLOsxd';
	$sToken = $_POST['token'];
	$sUsername = trim($_POST['username']);
	$sPassword = trim($_POST['password']); 
	$sPassword2 = trim($_POST['password2']);

	//Newest standard using ARGON requires php v7.2.0:
	// $hPassword password_hash('$sPassword.$sPeber', PASSWORD_ARGON2I);
	$hPassword = password_hash($sPassword.$sPeber, PASSWORD_DEFAULT);
	$bPassword = password_verify($sPassword.$sPeber, $hPassword);

				$response = urlencode($_POST["captcharesponse"]);
	
					$url = 'https://www.google.com/recaptcha/api/siteverify';
					$data = array(
						'secret' => '6LfROTMUAAAAACNIcHbzt_AcY5UyCnxjjIaRZlaX',
						'response' => $response
					);
					$options = array(
						'http' => array (
							'method' => 'POST',
							'header' => 
								"Content-Type: application/x-www-form-urlencoded",
							'content' => http_build_query($data)
						)
					);
					$context  = stream_context_create($options);
					$verify = file_get_contents($url, false, $context);
					$captcha_success=json_decode($verify);
					error_log($verify, 0);
					if ($captcha_success->success==false) {
						echo "You are a bot! Go away!";
					} else if ($captcha_success->success==true) {
	


	if(checkCSRF()){ 
		if ($sPassword !== $sPassword2){
			echo 'The passwords do not match.';
		}
		else{

				$uppercase = preg_match('@[A-Z]@', $sPassword);
				$lowercase = preg_match('@[a-z]@', $sPassword);
				$number    = preg_match('@[0-9]@', $sPassword);

				if(!$uppercase || !$lowercase || !$number || strlen($sPassword) < 8) {
				  echo 'password invalid';
				}
				else {

				//Check if the token from the session matches the one which was sent to the server from the form:
				if ($sToken !== $_SESSION['token']){
					echo 'Your attempt of CSRF has been prevented and logged.';
					error_log('CSRF attempt: '.$_SERVER['REMOTE_ADDR'],3,'/attempt.log');
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

							if($usernamecount != 0) { //Username already exists

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
	}
} else {
	echo 'CSRF check failed.';
}
}
}

function checkCSRF(){ 
	global $sToken;
	global $sUsername;
	global $sDate;
	
	if (isset($_SERVER['HTTP_REFERER'])){
		if($_SERVER['HTTP_REFERER'] != "http://localhost/webdevexam/"){
			error_log("CSRF attempted (Refferer). IP: ".$_SERVER['REMOTE_ADDR'].", Username: ".$sUsername.", Refferer: ".$_SERVER['HTTP_REFERER'].", Timestamp: ".$sDate, 0);
			return false;
		}
	}
	if ($sToken !== $_SESSION['token']){
		error_log("CSRF attempted (Token). IP: ".$_SERVER['REMOTE_ADDR'].", Username: ".$sUsername.", Refferer: ".$_SERVER['HTTP_REFERER'].", Timestamp: ".$sDate, 0);
		return false;
	}
	else {
		return true;
	}
}
?>