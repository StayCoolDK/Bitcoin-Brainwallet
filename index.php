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
				<input type="submit" class="loginbtn" value="Login">
				<div class="link"><a class="fa fa-user-plus"> Register account</a></div>
			</form>
		</div>
	</div>
</div>

<div id="wdw-register" class="wdw">
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
					<input type="submit" class="registerbtn" value="Register">
					<div class="returnlink"><a class="fa fa-sign-in"> Return to login</a></div>
				</form>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="./js/sweetalert.min.js"></script>
<script src="./js/main.js"></script>


</body>
</html>