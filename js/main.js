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

$(document).on("click", ".link", function(){
					swal({
					  title: "Registration",
					  text: "Write a username.",
					  type: "input",
					  showCancelButton: true,
					  closeOnConfirm: false,
					  animation: "slide-from-top",
					  inputPlaceholder: "Username"
					},
					function(sUsername){
					  if (sUsername === false) return false;
					  
					  if (sUsername === "") {
					    swal.showInputError("You need to write a username!");
					    return false
					  }
					  
					  else { //Not empty or false username, continue to password input.
							swal({
							  title: "Registration",
							  text: "Write a password.",
							  type: "input",
							  inputType: "password",
							  showCancelButton: true,
							  closeOnConfirm: false,
							  animation: "slide-from-top",
							  inputPlaceholder: "Password"
							},
							function(sPassword){
								  if (sPassword === false) return false;
								  
								  if (sPassword === "") {
								    swal.showInputError("You need to write a password!");
								    return false
								  }
								  else {
								  	swal("POST", ""+sUsername+" & "+sPassword, "success");
								  }
							});
							  	  
						 }
					});
})

