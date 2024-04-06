<?php
include("../include/config.php");


if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {

    header('Location: index.php');
} else{
    $adminId = $_GET['id'];
    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];

    if(isset($_POST['submit']) ){


        $admin_type = $_POST["admin_op"];
        $name = mysqli_real_escape_string($connection,$_POST['fullname']);

        
        if( isset($_POST['email']) and !empty($_POST['email']) ){
                
            $validate_email = mysqli_real_escape_string($connection,$_POST['email']);  
          
            $query = "SELECT * FROM admin WHERE id != $adminId AND admin_mail='$validate_email' ";
            $result = mysqli_query($connection, $query);

            if(mysqli_num_rows($result) > 0){
                $email_err_msg = '<b class="text-danger text-center">Email already exists try again.</b>';
            }
        }


        if(isset($_POST['confirm_password']) and !empty($_POST['confirm_password'])){
                
                if($_POST['confirm_password'] != $_POST['password']){
                    $pass_confirm_err_msg = '<b class="text-danger text-center">Please write same password in both fields</b>';
                }else{
                    $password = password_hash(mysqli_real_escape_string($connection,$_POST['password']),PASSWORD_BCRYPT);
                                        
                }
        }
           

        if(isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"]) ){
            $image_folder = "images/admin/";
            $delete_image = 'yes';

            $file_path = $image_folder . basename($_FILES["profilePic"]["name"]);
            $upload_status = 1;
            $image_file_type = pathinfo($file_path,PATHINFO_EXTENSION);
            $check_image = getimagesize($_FILES["profilePic"]["tmp_name"]);

            if($check_image !== false) {
                
                $upload_status = 1;
            }else{
                $image_err_msg  = '<b class="text-danger">that file is not an image</b>';
                $upload_status = 0;
            }
            
            
            if ($_FILES["profilePic"]["size"] > 5000000) {
                $image_err_msg =  '<b class="text-danger">Sorry, your file is too large.</b>';
                $upload_status = 0;
            }
            

            if($image_file_type != "jpg" and $image_file_type != "png" and $image_file_type != "jpeg"
            and $image_file_type != "gif" ) {
                $image_err_msg =  '<b class="text-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed</b>';
                $upload_status = 0;
            }

           
            if ($upload_status != 0) {
                $temp = explode(".", $_FILES["profilePic"]["name"]);
                $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $image_folder . $newfilename)) {
                    echo "<script>console.log('image moved successfully')</script>";
                    
                } else {
                    $image_err_msg =  '<b class="text-danger">Sorry, there was an error uploading your file';
                }
            }

        }else{
            $newfilename =  $_POST['picValue'];
            $delete_image = 'no';
        }



        if((isset($name) and !empty($name) ) and ( isset($admin_type) and !empty($admin_type) ) and ( isset($validate_email) and !empty($validate_email) ) and ( isset($password) and !empty($password) ) and ( isset($newfilename) and !empty($newfilename) ) ){

            $verify_email = "SELECT * FROM admin WHERE id != $adminId AND admin_mail = '$validate_email'";
            $check_res = mysqli_query($connection, $verify_email);

            if(mysqli_num_rows($check_res) > 0){
                $email_err_msg = '<b class="text-danger text-center">This email already exists try another one</b>';
            }else{

                $update_query = "UPDATE admin SET  
                name='$name',
                admin_mail='$validate_email',  
                password='$password', 
                profilePic='$newfilename',
                type = '$admin_type'
                WHERE id=$adminId" ;


                if(mysqli_query($connection, $update_query)){
                    
                    if($delete_image == 'yes'){
                        unlink("images/admin/{$_POST['picValue']}");
                    }
                    header('Location: home.php?back=2');
                }else{
                    $update_err_msg = '<div class="alert alert-danger text-center">
                        <span>unable to update try again later ....</span>
                    </div>';
                }            
            }
        } 
    }


    if(isset($_GET['id'])){

        if($is_admin == 'yes' or $loginId==$adminId) {

            $query = "SELECT * FROM admin WHERE id=$adminId";
            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                while( $row = mysqli_fetch_assoc($result) ){
                    $admin_image = $row["profilePic"];
                    $admin_name = $row["name"];
                    $admin_email = $row["admin_mail"];
                    $account_type = $row["type"];
            
            }
            }
        }else header('Location: home.php?back=1');    

    }else header('Location: home.php?back=1');


include('header.php');
?>
	<div id="wrapper" class="clearfix">

		<div id="left_sidebar">
			<div class="container clearfix">

				<nav>
					<ul>
						<li class="current"><a href="home.php"><i class="icon-home2"></i>Home</a></li>

                        <li><a href="categorie.php"><i class="icon-book2"></i>Categories</a></li>

						<li><a href="courses.php"><i class="icon-book3"></i>Courses</a></li>

						<li><a href="content.php"><i class="icon-line-content-left"></i>Content</a> </li>

						<li><a href="blog.php"><i class="icon-blogger"></i>Blog</a></li>

						<li><a href="library.php"><i class="icon-line-align-center"></i>Library</a></li>

						<li><a href="instructors.php"><i class="icon-guest"></i>Instructors</a></li>

                        <li><a href="team.php"><i class="icon-users"></i>Team</a></li>

                        <li><a href="logout.php"><i class="icon-line-is-power"></i>Logout</a></li>

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

                            if(isset($image_err_msg) or isset($pass_confirm_err_msg) or isset($update_err_msg)){
                            echo "<div class='alert alert-danger'>";
                                
                                echo "Please fill the form carefully and correctly<br>";
                                
                                echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                                </div>";    

                            }

                        ?>
                        
                        <h3>Update Admin</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nameId" class="custom_label">Full Name</label>
                                <input type="text" id="nameId" placeholder="Full Name" value="<?php if(isset($admin_name)) echo $admin_name; ?>" name="fullname" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+" >
                            </div>

                            <div class="form-group">                    
                                <label class="custom_label">Admin Type</label>
                                    <select class="form-control custom_input"  name="admin_op">
                                    <option value="">Select access control</option>
                                    <option <?php if($account_type == 'yes') { ?> selected <?php } ?> value="yes" >all access control</option>
                                    <option <?php if($account_type == 'no') { ?> selected <?php } ?> value="no">restricted access control</option>

                                    </select>
                            </div>

                            <div class="form-group">
                                <label for="emailId" class="custom_label">Email</label>
                                <input type="email" id="emailId" placeholder="Email" value="<?php 
                                if(isset($admin_email)) echo $admin_email; ?>" name="email" class="form-control custom_input" title="jeyfason@mail.me" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                            </div>
                            <div class="form-group">
                            </div>
                            <div class="form-group">       
                                <label class="btn bg_slate custom_label" style="font-size: 11px;" for="my-file-selector">
                                    <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                                Change Profile Picture
                                </label>
                                <span class='label bg_slate' id="upload-file-info"></span>
                                <?php if(isset($image_err_msg)){ echo $image_err_msg; } ?>
                            </div>
                            <div class="form-group">
                                <label for="passwordId1" class="custom_label">Password</label>
                                <input type="password" id="passwordId1" placeholder="Password" name="password" class="form-control custom_input" required minlength="6">
                            </div>
                            <div class="form-group">
                                <label for="passwordid2" class="custom_label">Confirm Password</label>
                                <input type="password" id="passwordid2" placeholder="Confirm Password" name="confirm_password" class="form-control custom_input" required minlength="6">
                                <?php if(isset($pass_confirm_err_msg)){ echo $pass_confirm_err_msg; } ?>
                            </div>

                            <input type="hidden" value="<?php if(isset($admin_image)) echo $admin_image; ?>" name="picValue" />
                            <div class="form-group">
                                <button name="submit" class="btn btn-block bg_slate" type="submit">update admin</button>
                            </div>
                        </form>

                    </div>
				</div>
			</div>
		</section>

<?php include('footer.php'); 
}
?>