<?php
session_start();
$_SESSION['token'] = bin2hex(random_bytes(32));
$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html>

<head>
	<title>Web Sec - Exam Project</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="stylesheet" type="text/css" href="./css/sweetalert.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
	<style>
	.cmtUsername {
		text-decoration: underline;
	}
	.g-recaptcha {
		    transform: scale(0.825);
    transform-origin: 0 0;
    margin-left: 5px;
	}
	</style>
</head>
<body>
<div id="wdw-login" class="wdw">
	<div class="login">
		<div class="login-screen">
			<div class="app-title">
				<h1 class="h1title">Login</h1>
			</div>

			<form method="POST" class="loginform">
				<input type="text" name="username" class="username" placeholder="&#xf2be; Username" autocomplete="on" required>
				<br>
				<input type="password" name="password" class="password" placeholder="&#xf084; Password" autocomplete="on" required>
				<br><br>
				<input type="hidden" name="CSRFToken" class="CSRFToken" value="<?php echo $token; ?>" >
				<div class="g-recaptcha" id="RecaptchaField1" data-sitekey="6LfROTMUAAAAACGsoVmge9vHtyN3Kqjmtn8ciNwT"></div>
				<input type="submit" class="loginbtn" value="Login">
				<div class="link"><a class="fa fa-user-plus"> Register account</a></div>
			</form>
		</div>
	</div>
</div>
<div id="wdw-register" class="wdw" style="display: none">
	<div class="register">
		<div class="register-screen">
			<div class="app-title">
				<h1 class="h1title">Register</h1>
			</div>
				<form method="POST" class="registerform">
					<input type="text" name="username" class="rUsername" placeholder="&#xf2be; Username" autocomplete="on" required>
					<br>
					<input type="password" name="password" class="rPassword" placeholder="&#xf084; Password" required><br>
					<input type="password" name="password2" class="rPassword2" placeholder="&#xf084; Re-type Password" required>
					<br><br>
					<input type="hidden" name="CSRFToken" class="rCSRFToken" value="<?php echo $token; ?>" >
					<div class="g-recaptcha" id="RecaptchaField2" data-sitekey="6LfROTMUAAAAACGsoVmge9vHtyN3Kqjmtn8ciNwT"></div>
					<input type="submit" class="registerbtn" value="Register">
					<div class="returnlink"><a class="fa fa-sign-in"> Return to login</a></div>
				</form>
		</div>
	</div>
</div>
<div id="wdw-comment" class="wdw" style="display: none">
	<div class="comment">
		<div class="comment-screen">
			<div classs="app-title">
				<h1 title="h1title">Leave a comment! :-)</h1>
			</div>
			<div class="comments"></div>
				<form method="POST" class="commentform">
					<input type="text" class="sComment" name="comment" placeholder="&#xf27b; Comment" required>
					<input type="hidden" name="CSRFToken" class="cCSRFToken" value="<?php echo $token ?>" >
					<input type="submit" class="commentbtn" value="Comment">
				</form>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="./js/sweetalert.min.js"></script>
<script src="./js/main.js"></script>


</body>
</html>