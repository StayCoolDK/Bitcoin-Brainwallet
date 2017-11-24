
    var CaptchaCallback = function() {
        recaptcha1 = grecaptcha.render('RecaptchaField1', {'sitekey' : '6LfROTMUAAAAACGsoVmge9vHtyN3Kqjmtn8ciNwT'});
        recaptcha2 = grecaptcha.render('RecaptchaField2', {'sitekey' : '6LfROTMUAAAAACGsoVmge9vHtyN3Kqjmtn8ciNwT'});
    };

function LoadComments(){
	$.ajax ({
		url: "./php/getcomments.php",
		method: "get",
		dataType: "JSON"
	}).done(function(jData){
    $(".comment-screen").empty();
    $(".comment-screen").append('<div classs="app-title">\
                                 <h1 title="h1title">Leave a comment! :-)</h1>\
                                 </div>');
		for(var i = 0; i < jData.length; i++){
			$(".comment-screen").append('<p class="comments"><b>Author:</b> '+jData[i].username + '<br> Comment: ' + jData[i].comment+ '<br>' + 'Date posted: '+jData[i].datestamp+'<br></p>');
    }

	}).fail(function(jData){
    swal("There was an error retreiving the comments");
    console.log(jData);
  });
}

$(document).on("click", ".logoutbtn", function(e){
	$.ajax ({
		url: "./php/logout.php",
		method: "get"
	}).done(function(sData){
		if(sData == "session destroyed"){
      swal("Logged out", "You have been logged out.", "success");
      grecaptcha.reset(recaptcha1)
      grecaptcha.reset(recaptcha2)
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
                    token: $(".CSRFToken").val(),
                    response: $("#g-recaptcha-response").val()
		    }
		}).done(function(sData){
      switch(sData) {
        case "Login Success":
            LoadComments();
            $("#wdw-login").hide();
            $("#wdw-comment").show();
            $("body").append('<a href="#" class="logoutbtn">Logout</div>');
            break;
        case "Login Fail":
            swal("Incorrect login", "Your login was incorrect, please try again.", "warning");
            grecaptcha.reset(recaptcha1)
            break;
        case "You are a bot! Go away!":
            swal("Recaptcha", "Please solve the recaptcha.", "warning");
            break;
        case "You have tried to sign in too many times without succeding, please wait up till 5 minutes.":
            swal("BANNED", "You've unsuccessfully logged in too many times and you have been banned for 5 minutes.", "error");
            grecaptcha.reset(recaptcha1)
            break;
        case "CSRF check failed.":
            swal("CSRF attempt logged", "Your attempt of cross site request forgery has been logged. Better luck next time ;-)", "error");
            grecaptcha.reset(recaptcha1)
            break;
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
            token: $(".rCSRFToken").val(),
            captcharesponse: $("#g-recaptcha-response-1").val()
			}
		}).done(function(sData){

      switch(sData) {
        case "Registration successful": 
            swal("Thank you!", "Registration was successful - you can now login.", "success");
            $("#wdw-register").hide();
            $("#wdw-login").show();
            break;
        case "You are a bot! Go away!":
            swal("Recaptcha", "Please solve the recaptcha.", "warning");
            break;
        case "Username already exists":
            swal("Username already exists", "Please choose a different username", "warning");
            grecaptcha.reset(recaptcha2)
            break;
        case "There was an error verifying the password.":
            swal("There was an error validating the password", "Please retype your password", "warning");
            grecaptcha.reset(recaptcha2)
            break;
        case "CSRF check failed.":
            swal("CSRF attempt logged", "Your attempt of cross site request forgery has been logged. Better luck next time ;-)", "error");
            grecaptcha.reset(recaptcha2)
            break;
        case "The passwords do not match.":
            swal("Passwords do not match", "Please retype the passwords.", "warning");
            grecaptcha.reset(recaptcha2)
            break;
        case "password invalid":
            swal("Password is invalid.", "Please choose a strong password containining atleast 8 characters, with a combination of numbers, big & small letters.", "warning");
            grecaptcha.reset(recaptcha2)
            break;
      }
		}).fail(function(){
      Swal("Could not connect to server", "We were unable to establish a connection to the server, please try again later.", "error");
      grecaptcha.reset()
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

      switch(sData) {
          case "Successful":
              swal("Comment posted!", "Thank you. Your comment has been posted.", "success");
              LoadComments(); //Update comments.
              $(".sComment").val("");
              break;
          case "You need to be logged in to comment":
              swal("You need to login to comment", "Please login to comment.", "error");
              break;
          case "CSRF check failed.":
              swal("CSRF attempt logged", "Your attempt of cross site request forgery has been logged. Better luck next time ;-)", "error");
              break;
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
