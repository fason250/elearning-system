
<?php


	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require "PHPmailer/src/Exception.php";
	require "PHPmailer/src/PHPMailer.php";
	require "PHPmailer/src/SMTP.php";



if(isset($_POST['submit'])){ 
	
	$mail = new PHPMailer(true);
            

	if( isset($_POST['name']) && !empty($_POST['name'])){

		$name = trim($_POST['name']);

	}

	if(isset($_POST['phone']) && !empty($_POST['phone'])){
			
		$phone = trim($_POST['phone']);

	}

	

	if(isset($_POST['email']) && !empty($_POST['email'])){

		$email = trim($_POST['email']);
	}

	if( isset($_POST['msg']) && !empty($_POST['msg']) ){
		
		$user_msg = trim($_POST['msg']);
		
		
	}	


if( ( isset($name) && !empty($name) ) && ( isset($email) && !empty($email) ) && ( isset($user_msg) && !empty($user_msg) ) && ( isset($phone) && !empty($phone) ) ) {
	$subject = "Help us";
	// preparing mail body 
	$message = "Email: {$email} <br>";
	$message .= "Name: {$name} <br>";
	$message .= "Phone: {$phone} <br>>br>";
	$message .= "Message: {$user_msg} <br>";
	

	try {
        //Server settings
        $mail->isSMTP();     
        $mail->Host = "smtp.gmail.com";
		$mail->SMTPAuth = true;
		$mail->Username = "jeyfason25@gmail.com";
		$mail->Password = "kctkfecrxhiqzgab";
		$mail->SMTPSecure = "ssl";  
		$mail->Port = 465;             
		//Recipients
		$mail->setFrom("jeyfason25@gmail.com");
		$mail->addAddress($email);
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $message;

		if($mail->send()){
			$success= "<div style='background-color: green; padding: 8px 11px; color:white;' id='success_email'> Thank You For Your Feedback. <br>We Are Here For Achieve Your Expectations.<a class='close' data-dismiss='alert'>&times</a></div>";
		}else{
			$send_error = '<b style="background-color: red; color:white;padding: 8px 11px;">Fail to send Mail. Please check Your Email Address&#44; Name &amp; Fill the message BOX. </b>';
		}
	} catch (Exception $e) {
		$send_error=  "<b style='background-color: red; color:white;padding: 8px 11px;' > Message could not be sent {$e}</b>";
	}

	
	
	
}  


}   


include("header.php");


?>
		
		<section id="page-title" style="border-bottom: 1px solid darkslategray;">

			<div class="container clearfix">
				<h1 >Contact Us</h1>
				<span>Get in Touch with Us</span>
				
			</div>

		</section>

		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<div class="postcontent nobottommargin">

						<?php 

							if(isset($send_error) ){

								echo "<div class='alert alert-danger'>";
                            
                            	echo "Please fill the form carefully and correctly<br>";

                            	echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                            	</div>";
							}else if(isset($success)){

								if(isset($success)) echo $success;
							}

						?>

						<h3>Send us an Email</h3>

							<form class="nobottommargin" id="template-contactform" name="template-contactform" action="" method="post">

								<div class="form-process"></div>

								<div class="col_one_third">
									<label for="template-contactform-name" class="custom_label">Name <small>*</small></label>
									<input type="text" placeholder="Name" id="template-contactform-name" name="name" class="sm-form-control required custom_input" required/>
								</div>

								<div class="col_one_third">
									<label for="template-contactform-email" class="custom_label">Email <small>*</small></label>
									<input type="email" id="template-contactform-email" placeholder="Email" name="email" class="required email sm-form-control custom_input" />
								</div>

								<div class="col_one_third col_last">
									<label for="template-contactform-phone" class="custom_label">Phone</label>
									<input type="text" id="template-contactform-phone" placeholder="Only Digits" name="phone" value="" class="sm-form-control custom_label" />
								</div>

								<div class="clear"></div>
								<div class="clear"></div>

								<div class="col_full">
									<label for="template-contactform-message" class="custom_label">Message <small>*</small></label>
									<textarea placeholder="Message" class="required sm-form-control custom_input" id="template-contactform-message" name="msg" rows="6" cols="30"></textarea>
								</div>
								
								<div class="col_full">
									<button class="button button-3d nomargin" style="background-color: darkslategray; " type="submit" id="template-contactform-submit" name="submit" value="submit">Send Feedback</button>
								</div>

							</form>
						
					</div>

					<div class="sidebar col_last nobottommargin">

						<address>
							<strong>Office:</strong><br>
							kigali, Gisozi<br>
						</address>

						<abbr title="Phone Number"><strong>Phone:</strong></abbr> 0792330514<br>
						<abbr title="Email Address"><strong>Email:</strong></abbr> jeyfason25@gmail.com

					
						<div class="widget noborder notoppadding">

							<a target="_blank" href="#" class="social-icon si-small si-dark si-facebook">
								<i class="icon-facebook"></i>
							</a>

							<a target="_blank" href="#" class="social-icon si-small si-dark si-twitter">
								<i class="icon-twitter"></i>
							</a>

							<a target="_blank" href="#" class="social-icon si-small si-dark si-youtube">
								<i class="icon-youtube-play"></i>
							</a>

							<a target="_blank" href="#" class="social-icon si-small si-dark si-gplus">
								<i class="icon-gplus"></i>
							</a>

							<a target="_blank" href="#" class="social-icon si-small si-dark si-dropbox">
								<i class="icon-dropbox2"></i>
							</a>

							<a target="_blank" href="https://github.com/fason250" class="social-icon si-small si-dark si-github">
								<i class="icon-github"></i>
							</a>

						</div>

					</div>

				</div>

			</div>

		</section>
		<script defer>
		    document.querySelector(".close").addEventListener("click",()=>{
		        document.getElementById("success_email").style.display = "none";
		    })
		</script>

<?php include("footer.php"); ?>