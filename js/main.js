
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

$(document).on("submit", ".registrationform", function(e){
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
			$("#myModal").fadeOut();
		}
		else if(sData == "Username already exists") {
			swal("Username already exists", "Please choose a different one", "warning");
		}
	});
});


$(document).on("click", ".link", function(){

		var modal = document.getElementById('myModal');

		var btn = document.getElementById("myBtn");

		var span = document.getElementsByClassName("close")[0];

		modal.style.display = "block";


		span.onclick = function() {
		    modal.style.display = "none";
		}

		window.onclick = function(event) {
		    if (event.target == modal) {
		        modal.style.display = "none";
		    }
		}
					
});

