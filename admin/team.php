
<?php
include("../include/config.php");

if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {

    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];
    $message = " ";
    

    if( isset($_POST['submit']) ){

        if($is_admin == 'yes'){ 
            if( isset($_POST['fullname']) and !empty($_POST['fullname'])){
                $name = mysqli_real_escape_string($connection,$_POST['fullname']);
            }

            if(isset($_POST['qualification']) and !empty($_POST['qualification'])){
                    $qualification = mysqli_real_escape_string($connection,$_POST['qualification']);
            }


            if(isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"]) ){
            
                $target_dir = "images/team/";
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
                $message_picture =  '<b class="text-danger">Please Select Your Profile picture</b>';
            }


            if( ( isset($name) and !empty($name) )  and ( isset($newfilename) and !empty($newfilename) ) and ( isset($qualification) and !empty($qualification) )  ){


                $insert_query = "INSERT INTO team(name, image, qualification) VALUES ('$name','$newfilename','$qualification')";

                if(mysqli_query($connection, $insert_query)){                        
                   
                    header('Location: team.php#end');
                }else{
                    $submit_message = '<div class="alert alert-danger">
                       <p>failed to add member try again</p>
                    </div>';
                }

            } 
        }else{

             $message = "<div class='alert alert-danger'> 
                <p>you are not allowed to perform these operation</p><br>       
                <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
                </div>";    
        } 

    }


if(isset($_GET['success'])){
    $message = "<div class='alert alert-success'> 
    <p>Record Deleted successfully.</p><br>       
    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
    </div>";
}

if(isset($_GET['delete_id'])){ 

    $deluser = $_GET['delete_id'];

    if($is_admin == 'yes'){
                               
        $message = "<div class='alert alert-danger'> 
            <p>Are you sure want to delete this Record?</p><br>
                <form action='{$_SERVER['PHP_SELF']}?id={$deluser}' method='post'>
                   <input type='submit' class='btn btn-danger btn-sm'
                   name='confirm_delete' value='Yes' delete!>
                   <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>No thanks!</a>                         
                </form>
        </div>";
    } else {
        $message = "<div class='alert alert-danger'> 
        <p>you are not allowed to perform these operation</p><br>       
        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
        </div>";
    }
}


if(isset($_GET['back'])){

    $back = $_GET['back'];

    if($back!=2){
            $update_status = "<div class='alert alert-danger'> 
            <p>you are not allowed to perform these operation</p><br>       
            <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
        </div>";
    }else{

        $update_status = "<div class='alert alert-success'> 
    <p>Record Updated successfully.</p><br>       
    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
    </div>";
    }

} 


if(isset($_POST['confirm_delete'])){

    $id = $_GET['id'];
    $query2 = "SELECT * FROM team WHERE id='$id' ";
    $result2 = mysqli_query($connection, $query2);

    if(mysqli_num_rows($result2) > 0){
    
         while( $row2 = mysqli_fetch_assoc($result2) ){
                
                $base_directory = "images/team/";
                if(unlink($base_directory.$row2['image']))
                    $delVar = " ";
                  
         }
    }

    $query = "DELETE FROM team WHERE id='$id'";
    $result = mysqli_query($connection,$query);
    
    if($result){
        header("Location: team.php?success=1");
    } else {
        echo "failed to delete the record try again";
    }
}


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

                        <li class="current"><a href="team.php"><i class="icon-users"></i>Team</a></li>

                        <li><a href="logout.php"><i class="icon-line-power"></i>Logout</a></li>    

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

                    echo $message; 
                    if(isset($update_status)) echo $update_status;

                        if(isset($message_picture) or isset($submit_message)){
                            echo "<div class='alert alert-danger'>";
                            
                            echo "Please fill the form carefully and correctly<br>";

                            echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                            </div>";    

                        }

                 ?>
                 
					<h3>Add Team Member</h3>

                    <form action="" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="fullnameId1" class="custom_label">Full Name</label>
                        <input type="text" id="fullnameId1" placeholder="Full Name" name="fullname" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+" required>
                    </div>

                    <div class="form-group">
                        <label class="btn bg_slate" style="font-size: 11px;" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Change Profile Picture
                        </label>
                        <span class='label bg_slate' id="upload-file-info"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>
                    <div class="form-group">
                        <label for="qualificationId1" class="custom_label">Qualifications</label>
                        <input type="text" id="qualificationId1" placeholder="Qualifications" name="qualification" class="form-control custom_input" required>
                    </div>
                    <div class="form-group">
                        <button name="submit" class="btn btn-block bg_slate" type="submit">Add member</button>
                    </div>
                </form>
                        					    
    
    <table class="table table-striped table-bordered">
    <tr style="background-color: darkslategray; color: white;">
        <th>ID</th>
        <th>Picture</th>
        <th>Name</th>
        <th>Qualification</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php

        $query = "SELECT * FROM team";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
            while( $row = mysqli_fetch_assoc($result) ){
                echo "<tr>";
                    echo "<td>".$row["id"]."</td> <td><img src=images/team/".$row["image"]." style='object-fit: cover;' width='80px' height='80px'> </td> <td>".$row["name"]."</td><td>".$row["qualification"]."</td>";

                    echo '<td><a href="updateteam.php?id='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                    <span class="icon-edit"></span></a></td>';
                    
                    echo '<td><a href="team.php?delete_id='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                    <span class="icon-trash2"></span></a></td>';

                echo "</tr>";  
                }
        } else {
            echo "<div class='alert alert-danger'>You have no team<a class='close' data-dismiss='alert'>&times;</a></div>";
        }
        
            mysqli_close($connection);
        ?>

        <tr>
            <td colspan="9" id="end"><div class="text-center"><a href="team.php" type="button" class="btn btn-sm bg_slate"><span class="icon-plus"></span></a></div></td>
        </tr>
    </table>

    



					</div>


				</div>

			</div>

		</section>

<?php include('footer.php'); 
}

?>