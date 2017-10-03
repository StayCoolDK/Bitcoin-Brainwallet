
$(document).on("submit", ".loginform", function(e){

	e.preventDefault();
		$.ajax ({
			url: "./php/login.php",
			method: "post",
			data: {
		                username: $(".username").val(),
		                password: $(".password").val(),
		                token: $(".CSRFToken").val()
		    }
		}).done(function(sData){

		if (sData == "Login Success"){
				swal("Logged in", "Congratulations!", "success");
				//Fade in the wallet controlpanel
		}
		else if (sData == "Login Fail"){
				swal("WRONG", "WRONG!", "warning");
		}
		else if (sData == "You have tried to sign in too many times without succeding, please wait up till 5 minutes."){
				swal("BANNED", "You've unsuccessfully logged in too many times and you have been banned for 5 minutes.", "error");
		}
		else if(sData == "Your attempt of CSRF has been prevented and logged.") {
				swal("Fuck Off", "Your attempt of CSRF has been prevented and logged.", "warning");
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
						token: $(".rCSRFToken").val()
			}
		}).done(function(sData){


			if (sData == "Registration successful"){
				swal("Thank you!", "Registration was successful - you can now login.", "success");
			}
			else if(sData == "Username already exists") {
				swal("Username already exists", "Please choose a different username", "warning");
			}
			else if(sData == "There was an error verifying the password.") {
				swal("There was an error validating the password", "Please retype your password", "warning");
			}
			else if(sData == "Your attempt of CSRF has been prevented and logged.") {
				swal("Fuck Off", "Your attempt of CSRF has been prevented and logged.", "warning");
			}
		});
	
});

$(document).on("click", ".link", function(){
		$(this).parent().parent().parent().parent().hide();
		$("#wdw-register").fadeIn(500);
});

$(document).on("click", ".returnlink", function(){
		$(this).parent().parent().parent().parent().hide();
		$("#wdw-login").fadeIn(500);
});
