
$(document).on("submit", ".loginform", function(e){
	e.preventDefault();
		$.ajax ({
			url: "./php/login.php",
			method: "post",
			data: {
		                username: $(".username").val(),
		                password: $(".password").val()
		    }
		}).done(function(sData){

		if (sData == "Login Success"){
				swal("Logged in", "Congratulations!", "success");
		}
		else if (sData == "Login Fail"){
				swal("WRONG", "WRONG!", "warning");
		}
		else if (sData == "You have tried to sign in too many times without succeding, please wait up till 5 minutes."){
				swal("BANNED", "You've unsuccessfully logged in too many times and you have been banned for 5 minutes.", "error");
		}
		
		});
});

$(document).on("click", ".registerbtn", function(e){
	$(".registrationform").submit();
		e.preventDefault();
		$.ajax({
			url: "./php/registration.php",
			method: "post",
			data: {
						username: $(".rUsername").val(),
						password: $(".rPassword").val()
			}
		}).done(function(sData){

			if (sData == "Registration successful"){
				swal("Thank you!", "Registration was successful - you can now login.", "success");
			}
			else if(sData == "Username already exists") {
				swal("Username already exists", "Please choose a different one", "warning");
			}
		});
});

$(document).on("click", ".link", function(){

		$(this).parent().parent().parent().parent().remove();

		$("body").append('\
			<div id="wdw-register" class="wdw">\
				<div class="register">\
					<div class="register-screen">\
						<div class="app-title">\
							<h1 class="h1title">Register</h1>\
						</div>\
						<form method="POST" class="registerform">\
							<input type="text" name="username" class="rUsername" placeholder="username" required>\
							<br>\
							<input type="password" name="password" class="rPassword" placeholder="password" required>\
							<br><br>\
							<input type="submit" class="registerbtn" value="Register">\
							<div class="returnlink">Back to login page</div>\
						</form>\
					</div>\
				</div>\
			</div>\
			').hide().fadeIn(500);
							
});

$(document).on("click", ".returnlink", function(){

		$(this).parent().parent().parent().parent().remove();

		$("body").append('<div id="wdw-login" class="wdw">\
					<div class="login">\
						<div class="login-screen">\
							<div class="app-title">\
								<h1 class="h1title">Login</h1>\
							</div>\
							<form method="POST" class="loginform">\
								<input type="text" name="username" class="username" placeholder="username" required>\
								<br>\
								<input type="password" name="password" class="password" placeholder="password" required>\
								<br><br>\
								<input type="submit" class="loginbtn" value="Login">\
								<div class="link">Need to register?</div>\
							</form>\
						</div>\
					</div>\
				</div>\
		').hide().fadeIn(500);
});
