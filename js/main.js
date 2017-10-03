
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

$(document).on("submit", ".registerform", function(e){
  e.preventDefault();
		$.ajax({
			url: "./php/registration.php",
			method: "post",
			data: {
						username: $(".rUsername").val(),
						password: $(".rPassword").val(),
						email: $(".rEmail").val()
			}
		}).done(function(sData){


			if (sData == "Registration successful"){
				swal("Thank you!", "Registration was successful - you can now login.", "success");
			}
			else if(sData == "Username already exists") {
				swal("Username already exists", "Please choose a different one", "warning");
			}
			else if(sData == "There was an error verifying the password.") {
				swal("There was an error validating the password", "Please retype your password", "warning");
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
							<input type="text" name="username" class="rUsername" placeholder="&#xf2be; Username" autocomplete="on" required>\
							<br>\
							<input type="password" name="password" class="rPassword" placeholder="&#xf084; Password" required><br>\
							<input type="password" name="password2" class="rPassword2" placeholder="&#xf084; Re-type Password" required>\
							<br><br>\
							<input type="submit" class="registerbtn" value="Register">\
							<div class="returnlink"><a class="fa fa-sign-in"> Return to login</a></div>\
							<div class="terms"><a class="fa fa-file-text-o"> Privacy</a></div>\
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
								<input type="text" name="username" class="username" placeholder="&#xf2be; Username" required>\
								<br>\
								<input type="password" name="password" class="password" placeholder="&#xf084; Password" required>\
								<br><br>\
								<input type="submit" class="loginbtn" value="Login">\
								<div class="link"><a class="fa fa-user-plus"> Register account</a></div>\
							</form>\
						</div>\
					</div>\
				</div>\
		').hide().fadeIn(500);
});
