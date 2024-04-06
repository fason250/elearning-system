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

            if( isset($_POST['course_name']) and !empty($_POST['course_name'])){
                
                $course_name = mysqli_real_escape_string($connection,$_POST['course_name']);
            }

            
            if(isset($_POST["categorie_op"]) and !empty($_POST["categorie_op"])){

                $categorie_option = $_POST["categorie_op"];
            }else{
                $course_catg_err_msg = '<b class="text-danger text-center">Please select categorie option OR insert course categorie.</b>';
            }

            
            if(isset($_POST["book_op"]) and !empty($_POST["book_op"])){

                $book_option = $_POST["book_op"];
            }else{
                $book_error = '<b class="text-danger text-center">Please Select book option OR Insert Book.</b>';
            }

            
            if(isset($_POST["instructor_op"]) and !empty($_POST["instructor_op"])){

                $instructor_option = $_POST["instructor_op"];
            }else{
                $instructor_error = '<b class="text-danger text-center">Please select Instructor option OR insert Instructor information.</b>';
            }

           
            if( isset($_POST['description']) and !empty($_POST['description']) ){
                
                $description = mysqli_real_escape_string($connection,$_POST['description']);
                
            }

            
            if( isset($_FILES["course_cover_pic"]["name"]) and !empty($_FILES["course_cover_pic"]["name"]) ){
                $image_folder = "images/courses/";
                $image_path = $image_folder . basename($_FILES["course_cover_pic"]["name"]);
                $upload_status = 1;
                $image_file_type = pathinfo($image_path,PATHINFO_EXTENSION);
                $check_image = getimagesize($_FILES["course_cover_pic"]["tmp_name"]);

                if($check_image !== false) {
                    
                    $upload_status = 1;
                }else{
                    $message_picture  = '<b class="text-danger">File is not an image</b>';
                    $upload_status = 0;
                }
                
                if($_FILES["course_cover_pic"]["size"] > 5000000) {
                    $message_picture =  '<b class="text-danger">Sorry, your file is too large.</b>';
                    $upload_status = 0;
                }
                
                if($image_file_type != "jpg" and $image_file_type != "png" and $image_file_type != "jpeg"
                and $image_file_type != "gif" ) {
                    $message_picture =  '<b class="text-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed</b>';
                    $upload_status = 0;
                }
                
                if($upload_status != 0){
                    $temp = explode(".", $_FILES["course_cover_pic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));

                    if(move_uploaded_file($_FILES["course_cover_pic"]["tmp_name"], $image_folder . $newfilename)) {
                        echo "<script>console.log('image moved succeddfully')</script>";
                    }else{
                        $message_picture =  '<b class="text-danger">Sorry, there was an error uploading your file';
                    }
                }

            }



            if( ( isset($course_name) and !empty($course_name) ) and (isset($book_option) and !empty($book_option)) and (isset($instructor_option) and !empty($instructor_option)) and (isset($categorie_option) and !empty($categorie_option)) and (isset($description) and !empty($description)) and ( isset($newfilename) and !empty($newfilename) ) ){


                $insert_query = "INSERT INTO course(name, cover, description, categorieId, instructorId, bookId) VALUES ('$course_name','$newfilename','$description','$categorie_option','$instructor_option','$book_option')";

                if(mysqli_query($connection, $insert_query)){
                    header('Location: courses.php#end');
                }else{
                    $submit_err_msg = '<div class="alert alert-danger">
                        <span>failed to add course</span>
                    </div>';
                }    
            }

        }else{

            $message = "<div class='alert alert-danger'> 
                <p>you are not allowed to perform that operation</p><br>       
                <a type='button' class='btn b_slate btn-sm' data-dismiss='alert'>Cancel</a> 
                </div>";    
        }

    }


if(isset($_GET['success'])){
    $message = "<div class='alert alert-success'> 
    <p>Record Deleted successfully.</p><br>       
    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a></div>";
}

if(isset($_GET['delete_id'])){ 
    $course_id = $_GET['delete_id'];

    if($is_admin == 'yes'){
                       
        $message = "<div class='alert alert-danger'> 
                <p>Are you sure want to delete this Record?</p><br>
                <form action='{$_SERVER['PHP_SELF']}?id={$course_id}' method='post'>
                   <input type='submit' class='btn btn-danger btn-sm'
                   name='confirm_delete' value='Yes' delete!>
                   <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>No thanks!</a> 
                </form>
    
            </div>";
    }else{
        $message = "<div class='alert alert-danger'> 
        <p>you are not allowed to perform that operation</p></p><br>       
        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
        </div>";
    }
}



if(isset($_GET['back'])){

    $back = $_GET['back'];

    if($back!=2){
            $update_status = "<div class='alert alert-danger'> 
    <p>you are not allowed to perform that operation </p><br>       
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
    $query2 = "SELECT * FROM course WHERE id='$id' ";
    $result2 = mysqli_query($connection, $query2);

    if(mysqli_num_rows($result2) > 0){
        while( $row2 = mysqli_fetch_assoc($result2) ){
            unlink("images/courses/{$row2['cover']}"); 
        }
    }

   
    $query = "DELETE FROM course WHERE id=$id";
    $result = mysqli_query($connection,$query);
    
    if($result){
        header("Location: courses.php?success=1");

    }else{
        echo "<script>console.log('failed to delete')</script> ";
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
                        
						<li class="current"><a href="courses.php"><i class="icon-book3"></i>Courses</a></li>

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

                                    if(isset($message_picture) or isset($submit_err_msg) or isset($course_catg_err_msg) or isset($instructor_error) or isset($book_error) ){
                                        echo "<div class='alert alert-danger'>";
                                        
                                            echo "Please fill the form carefully and correctly<br>";

                                            echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                                        </div>";    

                                    }

                            ?>
                            
                            <h3>Add New Course</h3>

                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="nameID" class="custom_label">Course Name</label>
                                    <input type="text" id="nameID" placeholder="Full Name" name="course_name" class="form-control custom_input" required title="Only lower and upper case and space" pattern="[A-Za-z/\s]+">
                                </div>

                                <div class="form-group">                    
                                    <label class="custom_label">Book Selection</label>
                                    <select class="form-control custom_input" required  name="book_op">
                                    <option value="">Select Option</option>
                                    <?php 
                                        $query = "SELECT * FROM library";
                                        $result = mysqli_query($connection, $query);

                                        if(mysqli_num_rows($result) > 0){
                                            while( $row = mysqli_fetch_assoc($result) ){?>

                                    <option value="<?php echo $row['id']; ?>" > <?php echo $row['name']; ?>  </option>

                                    <?php       
                                            }
                                        }
                                    ?>
                                    </select>
                                    <?php if(isset($book_error)) echo $book_error; ?>
                                </div>

                                <div class="form-group">                    
                                    <label class="custom_label">Categorie Selection</label>
                                    <select class="form-control custom_input"  name="categorie_op">
                                    <option value="">Select Option</option>
                                    <?php 
                                        $query = "SELECT * FROM categories";
                                        $result = mysqli_query($connection, $query);

                                        if(mysqli_num_rows($result) > 0){
                                            while( $row = mysqli_fetch_assoc($result) ){?>

                                    <option value="<?php echo $row['id']; ?>" > <?php echo $row['categorie']; ?>  </option>

                                    <?php       
                                            } 
                                        }
                                    ?>
                                    </select>

                                <?php if(isset($course_catg_err_msg)) echo $course_catg_err_msg; ?>
                            </div>

                            <div class="form-group">                    
                                    <label class="custom_label">Instructor Selection</label>
                                    <select class="form-control custom_input"  name="instructor_op">
                                        <option value="">Select Option</option>
                                        <?php 
                                            $query = "SELECT * FROM instructor";
                                            $result = mysqli_query($connection, $query);

                                            if(mysqli_num_rows($result) > 0){
                                                while( $row = mysqli_fetch_assoc($result) ){
                                        ?>

                                        <option value="<?php echo $row['id']; ?>" > <?php echo $row['name']; ?>  </option>

                                        <?php       
                                                } 
                                            }
                                        ?>

                                    </select>
                                    <?php if(isset($instructor_error)) echo $instructor_error; ?>
                                </div>

                                <div class="form-group">
                                    <label class="btn bg_slate" for="my-file-selector" style="font-size: 11px;">
                                        <input id="my-file-selector" name="course_cover_pic" type="file" style="display:none;" required onchange="$('#uploaded_file').html($(this).val());">
                                        Profile Picture
                                    </label>
                                    <span class='label label-success' id="uploaded_file"></span>
                                    <?php if(isset($message_picture)){ echo $message_picture; } ?>
                                </div>

                                <div class="form-group">
                                    <label for="descriptionId1" class="custom_label">Description</label>
                                    <textarea id="descriptionId1" class="form-control custom_input" height="40px" resize="none" name="description" required ></textarea>
                                </div>

                                <div class="form-group">
                                    <button name="submit" class="btn btn-block bg_slate" type="submit">Submit</button>
                                </div>
                            </form>
                                    
                            <table class="table table-striped table-bordered">
                                <tr style="background-color: darkslategray;color:white;">
                                    <th>ID</th>
                                    <th>Cover Picture</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                <?php

                                    $query = "SELECT * FROM course";
                                    $result = mysqli_query($connection, $query);

                                    if(mysqli_num_rows($result) > 0){
                                    
                                        while( $row = mysqli_fetch_assoc($result) ){
                                                echo "<tr>";
                                                    echo "<td>".$row["id"]."</td> <td><img src=images/courses/".$row["cover"]." width='80px' height='80px'> </td> <td>".$row["name"]."</td>";
                                                    
                                                    echo '<td><a href="view.php?courseId='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                                                    <span class="icon-eye-open"></span></a></td>';

                                                    echo '<td><a href="updatecourses.php?id='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                                                    <span class="icon-edit"></span></a></td>';
                                                    
                                                    echo '<td><a href="courses.php?delete_id='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                                                    <span class="icon-trash2"></span></a></td>';

                                                echo "<tr>";  
                                            }
                                    }else{
                                        echo "<div class='alert alert-danger'>You have no Courses yet<a class='close' data-dismiss='alert'>&times</a></div>";
                                    }
                                
                                    mysqli_close($connection);
                                ?>

                                <tr>
                                    <td colspan="6" id="end"><div class="text-center"><a href="courses.php" type="button" class="btn btn-sm bg_slate"><span class="icon-plus"></span></a></div></td>
                                </tr>
                            </table>


                    </div>
				</div>
			</div>
		</section>

<?php include('footer.php'); 
}
?>