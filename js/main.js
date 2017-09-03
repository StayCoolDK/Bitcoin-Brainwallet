$(document).on("submit", "form", function(e){
	e.preventDefault();
		$.ajax ({
			url: "./php/login.php",
			method: "post",
			data: {
		                username: $(".username").val(),
		                password: $(".password").val()
		    }
		}).done(function(sData){
		if (sData == "You are now logged in."){
				swal("Logged in", "Congratulations!", "success");
		}
		else if (sData == "Wrong username or password"){
				swal("WRONG", "WRONG!", "warning");
		}
		else if (sData == "Error, you have tried to sign in too many times without succeding, please wait up till 5 minutes."){
				swal("BANNED", "BANNED!", "error");
		}
		});
});