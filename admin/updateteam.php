
<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {

	header('Location: index.php');
} else {

	$memberId = $_GET['id'];
	$loginName = $_SESSION['userName'];
	$loginId = $_SESSION['userId'];
	$is_admin = $_SESSION['accountType'];


	if( isset($_POST['submit']) ){

	   if( isset($_POST['fullname']) and !empty($_POST['fullname'])){
			  $name = mysqli_real_escape_string($connection,$_POST['fullname']);
		}


		if( isset($_POST['qualification']) and !empty($_POST['qualification'])){
			
			$qualification = mysqli_real_escape_string($connection,$_POST['qualification']);
		}


		if( isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"]) ){
			$target_dir = "images/team/";
			$del = 'yes';
			$target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			$check = getimagesize($_FILES["profilePic"]["tmp_name"]);
			if($check !== false) {                
				$uploadOk = 1;
			} else {
				$message_picture  = '<b class="text-danger">File is not an image</b>';
				$uploadOk = 0;
			}

			if ($_FILES["profilePic"]["size"] > 5000000) {
				$message_picture =  '<b class="text-danger">Sorry, your file is too large.</b>';
				$uploadOk = 0;
			}
		
			if($imageFileType != "jpg" and $imageFileType != "png" and $imageFileType != "jpeg"
			and $imageFileType != "gif" ) {
				$message_picture =  '<b class="text-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed</b>';
				$uploadOk = 0;
			}
		
			if ($uploadOk != 0) {
				$temp = explode(".", $_FILES["profilePic"]["name"]);
				$newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
				if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
					
				} else {
					$message_picture =  '<b class="text-danger">Sorry, there was an error uploading your file';
				}
			}

		}else{
			$newfilename =  $_POST['picValue'];
			$del = 'no';
		}

		if(( isset($name) and !empty($name) )  and ( isset($newfilename) and !empty($newfilename) ) and ( isset($qualification) and !empty($qualification) )  ){

				$insert_query = "UPDATE team set
				 name ='$name',  
				 image = '$newfilename', 
				 qualification = '$qualification' 
				 WHERE id = '$memberId'";

				if(mysqli_query($connection, $insert_query)){
					
					if($del == 'yes'){
					$base_directory = "images/team/";
					if(unlink($base_directory.$_POST['picValue']))
					$delVar = " ";
				}
				   
					header('Location: team.php?back=2');
				}else{
					$submit_message = '<div class="alert alert-danger">
						<p>failed to update data</p>
					</div>';
				}
			} 
		}


if(isset($_GET['id'])){

	$memberId = $_GET['id'];
	if( $is_admin == 'yes' ) {

	   $query = "SELECT * FROM team WHERE id=$memberId ";

		$result = mysqli_query($connection,$query);

		if(mysqli_num_rows($result) > 0){
			while( $row = mysqli_fetch_assoc($result) ){
				$memberPic = $row["image"];
				$memberName = $row["name"];
				$memberQualification = $row["qualification"];
			}
		}
	}else header('Location: team.php?back=1');    

}else header('Location: team.php?back=1');



include('header.php');

?>
	<div id="wrapper" class="clearfix">

		<div id="left_sidebar">
			<div class="container clearfix">

				<nav>
					<ul>
						<li><a href="home.php"><i class="icon-home2"></i>Home</a></li>

                        <li><a href="categorie.php"><i class="icon-book2"></i>Categories</a></li>

						<li><a href="courses.php"><i class="icon-book3"></i>Courses</a></li>

						<li><a href="content.php"><i class="icon-line-content-left"></i>Content</a> </li>

						<li><a href="blog.php"><i class="icon-blogger"></i>Blog</a></li>

						<li><a href="library.php"><i class="icon-line-align-center"></i>Library</a></li>

						<li><a href="instructors.php"><i class="icon-guest"></i>Instructors</a></li>

                        <li><a href="team.php"><i class="icon-users"></i>Team</a></li>

                        <li class="current"><a href="logout.php"><i class="icon-line-power"></i>Logout</a></li>    

					</ul>
				</nav>

			</div>
		</div>

		<section id="page-title" style="border-bottom: 1px solid darkslategray;">

			<div class="container clearfix">
				<h1 style="padding-block: 20px; text-transform: capitalize;">Welcome <b><?php if(isset($loginName)) echo $loginName; ?></b></h1>
			</div>

		</section>

		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

				<div class="postcontent nobottommargin">

                <?php
 

                        if(isset($message_picture) or isset($submit_message)){
                            echo "<div class='alert alert-danger'>";
                            
                            echo "Please fill the form carefully and correctly<br>";

                            echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                            </div>";    

                        }

                 ?>
                 
					<h3>Update Member</h3>

                    <form action="" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="fullnameId1" class="custom_label">Full Name</label>
                        <input type="text" id="fullnameId1" placeholder="Full Name" value="<?php if(isset($memberName)) echo $memberName; ?>" name="fullname" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+">
                    </div>

                    <div class="form-group">
                    <img src="images/team/<?php if(isset($memberPic)) echo $memberPic; ?>" style="object-fit: cover;" width="100px" height="100px">
                    </div>

                    <div class="form-group">
                        <label class="btn bg_slate" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Profile Picture
                        </label>
                        <span class='label bg_slate' id="upload-file-info"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>

                    <div class="form-group">
                        <label for="qualificationid1" class="custom_label">Qualifications</label>
                        <input type="text" id="qualificationid1" placeholder="Qualifications" value="<?php if($memberQualification) echo $memberQualification; ?>" name="qualification" class="form-control custom_input">
                    </div>

                    <input type="hidden" value="<?php if(isset($memberPic)) echo $memberPic; ?>" name="picValue"/>
                    <div class="form-group">
                        <button name="submit" class="btn btn-block bg_slate" type="submit">update member</button>
                    </div>
                </form>

		</div>


				</div>

			</div>

		</section>

<?php include('footer.php'); 
}

?>