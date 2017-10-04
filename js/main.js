
LoadComments();

function LoadComments(){
	$.ajax ({
		url: "./php/getcomments.php",
		method: "get",
		dataType: "JSON"
	}).done(function(jData){
		$(".comments").empty();
		for(var i = 0; i < jData.length; i++){
			$(".comments").append('<p class="comment">'+jData[i].comment + '<br>' + jData[i].datestamp + '<br>' + '<b>'+jData[i].username+ '</b>' + '<br></p>');
		}
	})
}

$(document).on("click", ".logoutbtn", function(e){
	//get to logout.php - session_destroy
	$.ajax ({
		url: "./php/logout.php",
		method: "get"
	}).done(function(sData){
		if(sData == "session destroyed"){
			swal("Logged out", "You have been logged out.", "success");
			$("#wdw-login").show();
			$("#wdw-comment").hide();
			$(".logoutbtn").hide();
		}
	})
})


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
				$("#wdw-comment").show();
				$("#wdw-login").hide();
				$("body").append('<a href="#" class="logoutbtn">Logout (Clear session)</div>')
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
		
		}).fail(function(){
			Swal("Could not connect to server", "We were unable to establish a connection to the server, please try again later.", "error");
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
						password2: $(".rPassword2").val(),
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
			else if(sData == "The passwords do not match."){
				swal("Passwords do not match", "Please retype the passwords.", "warning");
			}
			else if(sData == "password invalid"){
				swal("Password is invalid.", "Your password must contain 8 characters, with a combination of numbers and big & small letters.", "warning");
			}
		}).fail(function(){
			Swal("Could not connect to server", "We were unable to establish a connection to the server, please try again later.", "error");
		});
	
});

$(document).on("submit", ".commentform", function(e){
	e.preventDefault();
		$.ajax({
			url: "./php/postcomment.php",
			method: "post",
			data: {
					comment: $(".sComment").val(),
					token: $(".cCSRFToken").val()
			}
		}).done(function(sData){
			if (sData == "Successful"){
				swal("Comment posted!", "Thank you. Your comment has been posted.", "success");
				LoadComments(); //Update comments.
			}
			else if (sData == "You need to be logged in to comment"){
				swal("You need to login to comment", "Please login to comment.", "error");
			}
			else if(sData == "Your attempt of CSRF has been prevented and logged."){
				swal("Fuck Off", "Your attempt of CSRF has been prevented and logged.", "warning");
			}
		}).fail(function(){
			Swal("Could not connect to server", "We were unable to establish a connection to the server, please try again later.", "error");
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
