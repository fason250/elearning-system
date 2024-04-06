<?php 

include("../include/config.php");
    
if(isset($_POST['submit'])){

	if((isset($_POST["email"]) and !empty($_POST["email"])) and (isset($_POST["password"]) and !empty($_POST["password"]))){

		$email = mysqli_real_escape_string($connection, $_POST["email"]);
		$password = mysqli_real_escape_string($connection, $_POST["password"]);
		$check_sql = "SELECT * FROM admin WHERE admin_mail='$email'";
		$result = mysqli_query($connection, $check_sql);
				   
		if($result){
			
			while($row = mysqli_fetch_assoc($result)){
				if(password_verify($password,$row["password"])){
					$_SESSION['userId'] = $row['id'];
					$_SESSION['userName'] = $row['name'];
					$_SESSION['accountType'] = $row['type'];
					header('Location: home.php');  
				}                  
			}
		
		}

		
		if($row <= 0){

			$message_found = '<div class="text-danger text-center">please email or password not found try again</div>';

		}
	}
}

include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Jey fason" />
	<link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet"/>
	<link rel="stylesheet" href="../css/bootstrap.css"/>
	<link rel="stylesheet" href="../style.css"/>
	<link rel="stylesheet" href="../css/dark.css"/>
	<link rel="stylesheet" href="../css/font-icons.css"/>
	<link rel="stylesheet" href="../css/animate.css"/>
	<link rel="stylesheet" href="../css/magnific-popup.css"/>
	<link rel="stylesheet" href="../css/calendar.css"/>
	<link rel="stylesheet" href="../css/responsive.css"/>
	<link rel="stylesheet" href="../css/custom.css">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>intellistudy</title>
</head>
<body class="streched">

	<div id="wrapper" class="clearfix" >
		<section>
			<div class="content-wrap nopadding">
				<div class=" nopadding nomargin" style="width: 100%; height: 100vh; position: absolute; left: 0; top: 0; background: url('../images/login-bg.jpg') center center no-repeat; background-size: cover; opacity: 0.88;"></div>

				<div class="section nobg full-screen nopadding nomargin">
					<div class="container vertical-middle divcenter clearfix">

						<div class="panel panel-default divcenter noradius noborder" style="max-width: 400px;">
							<div class="panel-body" style="padding: 40px;" >
								<form id="login-form" name="login-form" class="nobottommargin" action="" method="post">
									<h3 class="center">Login to your Account</h3>

									<div class="col_full">
										<label for="login-form-email" class="custom_label" style="text-transform: capitalize;">Email:</label>
										<input type="email" id="login-form-username" name="email" value="" class="form-control not-dark" style="border-radius: 35px;border: 1px solid darkslateblue;" required title="email address"/>
									</div>

									<div class="col_full">
										<label for="login-form-password" style="text-transform: capitalize;">Password:</label>
										<input type="password" id="login-form-password" name="password" value="" class="form-control not-dark" style="border-radius: 35px;border: 1px solid darkslateblue;" minlength="6" required title="enter password"/>
									</div>

									<div class="col_full nobottommargin center">
										<button class="button button-3d button-black nomargin " id="login-form-submit" name="submit" value="login">Login</button>
										
									</div>	
									
								</form>

								<div class="line line-sm"></div>

								<div class="alert-danger center">
								
								<?php 

									if(isset($message_found)){ 
										echo "$message_found";
									}
								?>
									
								</div>	
							</div>
						</div>


						<div class="row center dark"><small>Copyrights &copy; <span id="copyright"></span> All Rights Reserved by Jey fason.</small></div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/plugins.js"></script>
	<script type="text/javascript" src="../js/functions.js"></script>
	<script>
		document.querySelector('#copyright').textContent = new Date().getFullYear();
	</script>

</body>
</html>