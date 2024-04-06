
<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {

    header('Location: index.php');
}else{

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];
    $message = "";
    

    if( isset($_POST['submit']) ){     
        if($is_admin == 'yes'){
            if(isset($_POST["admin_op"]) and !empty($_POST["admin_op"])){

                $admin_type = $_POST["admin_op"];
            } else {
                $admin_error = '<b class="text-danger text-center">Please select access control option.</b>';
            }       


            if( isset($_POST['fullname']) and !empty($_POST['fullname'])){
                $name = mysqli_real_escape_string($connection,$_POST['fullname']);
            }



            if(isset($_POST['email']) and !empty($_POST['email'])){
                $validate_email = mysqli_real_escape_string($connection,$_POST['email']);    
                $query = "SELECT * FROM admin WHERE admin_mail='$validate_email' ";
                $result = mysqli_query($connection, $query);
                if(mysqli_num_rows($result) > 0){
                    $email_err_msg = '<b class="text-danger text-center">email is already exist</b>';
                }else{
                $email = mysqli_real_escape_string($connection,$_POST['email']);
                
                }
            }

            if(isset($_POST['confirm_password']) and !empty($_POST['confirm_password'])){
                
                if($_POST['confirm_password'] != $_POST['password']){
                    $pass_not_match_err = '<b class="text-danger text-center">password not matched</b>';
                  }else{

                    $password = password_hash(mysqli_real_escape_string($connection,$_POST['password']),PASSWORD_BCRYPT);
                }
                
            }
           

            if( isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"]) ){
                
                $image_folder = "images/admin/";
                $image_path = $image_folder . basename($_FILES["profilePic"]["name"]);
                $upload_status = 1;
                $image_file_type = pathinfo($image_path,PATHINFO_EXTENSION);
                $check_image = getimagesize($_FILES["profilePic"]["tmp_name"]);

                if($check_image !== false) {
                    
                    $upload_status = 1;
                }else{
                    $image_err_msg  = '<b class="text-danger">File is not an image</b>';
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
                
                if($upload_status != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if(move_uploaded_file($_FILES["profilePic"]["tmp_name"], $image_folder . $newfilename)) {
                        echo "<script> console.log('image moved successfully')</script>";
                    }else{
                        $image_err_msg =  '<b class="text-danger">Sorry, there was an error uploading your file';
                    }
                }
            }



            if((isset($name) and !empty($name) ) and (isset($admin_type) and !empty($admin_type)) and (isset($email) and !empty($email)) and (isset($password) and !empty($password)) and (isset($newfilename) and !empty($newfilename))){

                $check_email = "SELECT * FROM admin WHERE admin_mail = '$email'";
                $check_res = mysqli_query($connection, $check_email);

                if(mysqli_num_rows($check_res) > 0){
                    $email_err_msg = '<b class="text-danger text-center">This email already exists</b>';
                }else{

                    $insert_query = "INSERT INTO admin (name, admin_mail,  password, profilePic, type) VALUES ('$name','$email','$password','$newfilename','$admin_type')";

                    if(mysqli_query($connection, $insert_query)){
                    
                        header('Location: home.php#end');
                    }else{
                        $submit_message = '<div class="alert alert-danger">
                                <span>failed to submit data try again....</span>
                            </div>';
                    }

                }       
            } 

    }else{

         $message = "<div class='alert alert-danger'> 
            <p>you do not have permission to perfom that operation</p><br>       
            <a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Cancel</a> 
            </div>";    
    } 
}


if(isset($_GET['success'])){

    $message = "<div class='alert alert-success'> 
    <p>record deleted successfully!!</p><br>       
    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
    </div>";

}

if(isset($_GET['delete_id'])){ 

    $deluser = $_GET['delete_id'];

    if($is_admin == 'yes'){
       if($deluser != 1) {             
            $message = "<div class='alert alert-danger'> 
                <p>Are you sure want to delete this Admin?</p><br>
                    <form action='{$_SERVER['PHP_SELF']}?id=$deluser' method='post'>
                    <input type='submit' class='btn btn-danger btn-sm'
                    name='confirm_delete' value='Yes' delete!>
                    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>No thanks!</a> 
                        
                    </form>
                </div>";
        }else{

            $message = "<div class='alert alert-danger'> 
            <p>you are not allowed to perform that operation!</p><br>       
            <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
            </div>";
        }        
    }else{
        $message = "<div class='alert alert-danger'> 
        <p>you are not  allowed to perform this opertaion</p><br>       
        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
        </div>";
    }
}



if(isset($_GET['back'])){

    $back = $_GET['back'];

    if($back!=2){
        $update_err_msg = "<div class='alert alert-danger'> 
        <p>you are not allowed to perform that operation Dude!!! </p><br>       
        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
        </div>";
    }else{
        $update_err_msg = "<div class='alert alert-success'> 
        <p>Record Updated successfully.</p><br>       
        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
        </div>";
    }

} 


 
if(isset($_POST['confirm_delete'])){

    $id = $_GET['id'];
    $query2 = "SELECT * FROM admin WHERE id=$id ";
    $result2 = mysqli_query($connection, $query2);

    if(mysqli_num_rows($result2) > 0){
        while( $row2 = mysqli_fetch_assoc($result2) ){
            unlink("images/admin/{$row2['profilePic']}");
        }
    }

    $query = "DELETE FROM admin WHERE id=$id";
    $result = mysqli_query($connection,$query);
    
    if($result){
        header("Location: home.php?success=1");
    }else{
        echo "failed to delete data please try again!!!";
    }
}

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

                        <li><a href="logout.php"><i class="icon-line-power"></i>Logout</a></li>    

					</ul>
				</nav>

			</div>
		</div>
        
        <section id="page-title" style="padding: 0px;">

            <div class="container clearfix">
             <h1 style="padding-block: 20px; text-transform: capitalize;">Welcome <b><?php if(isset($loginName)) echo $loginName; ?></b></h1>
            </div>
            <div id="page-menu-wrap" style="background-color: darkslategray;"></div>

        </section>

		<section id="content">
			<div class="content-wrap">
				<div class="container clearfix">
				    <div class="postcontent nobottommargin">
                        <?php

                            echo $message; 
                            if(isset($update_err_msg)) echo $update_err_msg;

                                if(isset($message_name) or isset($image_err_msg) or isset($message_pass) or isset($pass_not_match_err) or isset($submit_message) or isset($admin_error)){
                                    
                                    echo "<div class='alert alert-danger'>";
                                    
                                    echo "Please fill the form carefully and correctly<br>";

                                    echo "<a type='button' class='btn btn-default btn-sm' data-dismiss='alert'>Cancel</a>
                                    </div>";    

                                }

                        ?>
                        
                        <h3>Add New Admin</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nameId" class="custom_label">Full Name</label>
                                <input type="text" id="nameId" placeholder="Full Name" name="fullname" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+" required>
                            </div>

                            <div class="form-group">                    
                                <label class="custom_label">Admin Type</label>
                                <select class="form-control custom_input"  name="admin_op" required>
                                    <option value="">select access control</option>
                                    <option value="yes">all access control</option>
                                    <option value="no">restricted access control</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="emailId" class="custom_label">Email</label>
                                <input type="email" id="emailId" placeholder="Email" name="email" class="form-control custom_input" title="jeyfason@gmail.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="btn bg_slate" style="font-size: 11px;" for="my-file-selector">
                                    <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                                    Profile Picture
                                </label>
                                <span class='label label-success' id="upload-file-info"></span>
                                <?php if(isset($image_err_msg)){ echo $image_err_msg; } ?>
                            </div>

                            <div class="form-group">
                                <label for="passwordId1" class="custom_label">Password</label>
                                <input type="password" id="passwordId1" placeholder="Password" name="password" class="form-control custom_input" required minlength="6">
                            </div>

                            <div class="form-group">
                                <label for="passwordId2" class="custom_label">Confirm Password</label>
                                <input id="passwordId2" type="password" placeholder="Confirm Password" name="confirm_password" class="form-control custom_input" required minlength="6">
                            </div>

                            <div class="form-group">
                                <button name="submit" class="btn btn-block bg_slate" type="submit">Submit</button>
                            </div>
                        </form>
                                
                                    
                        <table class="table table-striped table-bordered">
                            <tr style="background-color: darkslategray;color:white;">
                                <th>ID</th>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            <?php

                                $query = "SELECT * FROM admin";

                                $result = mysqli_query($connection, $query);

                                if(mysqli_num_rows($result) > 0){
                                
                                while( $row = mysqli_fetch_assoc($result) ){
                                        echo "<tr>";
                            echo "<td>".$row["id"]."</td> <td><img style='object-fit: cover;' src=images/admin/".$row["profilePic"]." width='80px' height='80px'> </td> <td>".$row["name"]."</td> <td> ".$row["admin_mail"]."</td>";

                                            echo '<td><a href="updateadmin.php?id='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                                            <span class="icon-edit"></span></a></td>';
                                            
                                            echo '<td><a href="home.php?delete_id='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                                            <span class="icon-trash2"></span></a></td>';

                                            echo "<tr>";  
                                        }
                                } else {
                                    echo "<div class='alert alert-danger'>You have no admin<a class='close' data-dismiss='alert'>&times;</a></div>";
                                }
                                
                                    mysqli_close($connection);
                                ?>

                                <tr>
                                    <td colspan="6" id="end"><div class="text-center"><a href="home.php" type="button" class="btn btn-sm bg_slate"><span class="icon-plus"></span></a></div></td>
                                </tr>
                        </table>
			        </div>
                </div>
            </div>
	</section>

<?php include('footer.php'); 

}

?>