<?php
session_start();

	if(isset ($_POST['username']) && isset ($_POST['password']) && isset($_POST['token'])) {

				$now = new DateTime();
				$sDate = $now->format('Y-m-d H:i:s');  // MySQL datetime format
				$sPeber = 'Qls9j-3as2daLOsxd';
				$sUsername = trim($_POST['username']);
				$sPassword = trim($_POST['password']);
				$sToken = $_POST['token'];
				$response = urlencode($_POST["response"]);

				//Verify server side at Google whether or not the captcha was valid.
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
				error_log($verify, 0);
				$captcha_success=json_decode($verify);
				if ($captcha_success->success==false) {
					echo "You are a bot! Go away!";
				} else if ($captcha_success->success==true) {

				if(checkCSRF()){ 

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
			else {
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
