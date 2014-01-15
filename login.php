<?

if(!isset($_SESSION)) {
    session_start();
};

if ($_GET['error']) {
	$error_returned = 'Invalid username or password, please try again';
}

if ($_SESSION['logged_in'] != 1) {
	//header("Location: login.php"); 
    //exit;
}

if ($_GET['logout'] == 1) {
	unset($_SESSION['logged_in']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script>
		$(document).ready(function(){
		  $("form#loginForm").submit(function() { // loginForm is submitted

			var username = $('#username').attr('value'); // get username
			var password = $('#password').attr('value'); // get password

			var form_data = $("#loginForm").serialize();
			//console.log(form_data);
			if (username && password) { // values are not empty
			  $.ajax({
				type: "POST",
				url: "login_check.php",
				//contentType: "application/json; charset=utf-8",
				dataType: "json",
				data: form_data,
				// script call was *not* successful
				error: function(jqXHR, textStatus, errorThrown) {
 					console.log(jqXHR, textStatus, errorThrown);
				}, // error 
				// script call was successful 
				// data contains the JSON values returned
				success: function(return_data){
				  console.log(return_data);
				  if (return_data == false) { // script returned error
				    $('div#loginResult').html("<a href=\"#\" data-role=\"button\" data-icon=\"alert\" data-iconpos=\"notext\" data-theme=\"e\" data-inline=\"true\">Invalid username or password, please try again.</a>");
				    $('div#loginResult').addClass("error");
				  } // if
				  else { // login was successful
				    // $('form#loginForm').hide();
				    // $('div#loginResult').text("data.success: " + return_data.success 
				    //   + ", data.id: " + return_data.id);
				    // $('div#loginResult').addClass("success");
					window.location = 'index.php';
					return false;
				  } //else
				} // success
			  }); // ajax
			} // if
			else {
			  $('div#loginResult').html("<a href=\"#\" data-role=\"button\" data-icon=\"alert\" data-iconpos=\"notext\" data-theme=\"e\" data-inline=\"true\">Please enter a username and password</a>");
			  $('div#loginResult').addClass("error");
			} // else
			$('div#loginResult').fadeIn();
			return false;
		  });
			/*if ($('#username').attr('value') != "" && $('#password').attr('value') != "") {
				$('input').keypress(function (e) {
					if (e.which == 13) {
						$('#submit').blur();
            			$('#submit').focus().click();
						return false;
					}
				});				
			}*/

			$("#cancel").click(function(e) {
				e.preventDefault();
				$(".ui-grid-a").find('form')[0].reset();
			});
		});	
	</script>
	<script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
	<script src="js/jQuery.ui.datepicker.js"></script>
	<style>

	</style>
</head>
<body id="login">
<div data-role="page" class="">	
	<div class="ui-grid-a ui-responsive">
		<div id="loginResult"></div>
		<form id="loginForm" name="loginForm" method="POST" action="login_check.php">
			<label for="username">Username</label>
			<input type="text" id="username" name="username" />
			<label for="password">Password</label>
			<input type="password" id="password" name="password" autocomplete="off" />
			<div class="ui-block-a">
				<button data-icon="delete" data-theme="a" type="submit" id="cancel">Cancel</button>
			</div>
			<div class="ui-block-b">
				<button data-icon="check" data-theme="b" type="submit" id="submit">Login</button>
			</div>
			<input type="hidden" name="login_submitted" value="1">
		</form>
	</div>
</div>
</body>
</html>
