
<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

    header('Location: index.php');
} else{

    $course_id = $_GET["id"];
    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];

    if( isset($_POST['submit']) ){
        if($is_admin == "yes"){

        if( isset($_POST['description']) && !empty($_POST['description']) ){
            
            $description = mysqli_real_escape_string($connection,$_POST['description']);
        }
    
        if(isset($_POST["categorie_op"]) && !empty($_POST["categorie_op"])){

            $categorie_option = $_POST["categorie_op"];
        }

        if(isset($_POST["book_op"]) && !empty($_POST["book_op"])){

            $book_option = $_POST["book_op"];
        } 

        if(isset($_POST["ins_op"]) && !empty($_POST["ins_op"])){

            $instructor_option = $_POST["ins_op"];
        }

        if( isset($_POST['course_name']) && !empty($_POST['course_name'])){
            
            $course_name = mysqli_real_escape_string($connection,$_POST['course_name']);
        }

        
            if(isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                $image_folder = "images/courses/";
                $delete_file = 'yes';
                $image_path = $image_folder . basename($_FILES["profilePic"]["name"]);
                $upload_status = 1;
                $image_file_type = pathinfo($image_path,PATHINFO_EXTENSION);
                $check = getimagesize($_FILES["profilePic"]["tmp_name"]);

                if($check !== false) {
                    
                    $upload_status = 1;
                }else{
                    $image_err_msg  = '<b class="text-danger">File is not an image</b>';
                    $upload_status = 0;
                }
                
                if ($_FILES["profilePic"]["size"] > 5000000) {
                    $image_err_msg =  '<b class="text-danger">Sorry, your file is too large.</b>';
                    $upload_status = 0;
                }
            
                if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg"
                && $image_file_type != "gif" ) {
                    $image_err_msg =  '<b class="text-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed</b>';
                    $upload_status = 0;
                }
            
                if ($upload_status != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profile_pic . $newfilename)) {
                        echo "<script>console.log('file moved succeffully')</script>";
                    }else{
                        $image_err_msg =  '<b class="text-danger">Sorry, there was an error uploading your file';
                    }
                }

            }else{
                $newfilename = $_POST["picValue"];
                $delete_file = 'no';
            }



            if( ( isset($course_name) && !empty($course_name) ) && (isset($book_option) && !empty($book_option)) && (isset($instructor_option) && !empty($instructor_option)) && (isset($categorie_option) && !empty($categorie_option)) && (isset($description) && !empty($description)) && ( isset($newfilename) && !empty($newfilename) ) ){


                    $insert_query = "UPDATE course SET
                    name = '$course_name', 
                    cover = '$newfilename',
                    description = '$description',
                    categorieId = '$categorie_option', 
                    instructorId = '$instructor_option',
                    bookId = '$book_option'
                    WHERE id = $course_id ";


                    if(mysqli_query($connection, $insert_query)){
                        
                        if($delete_file == 'yes') unlink("images/courses/{$_POST['picValue']}");
                    }  
                    header('Location: courses.php?back=2');

                }else{
                    $submit_message = '<div class="alert alert-danger">
                    <span>failed to update the course try again</span>
                    </div>';
                    }        
                }
        } 


    if(isset($_GET['id'])){

        $course_id = $_GET["id"];

        if( $is_admin == 'yes') {

            $query = "SELECT * FROM course WHERE id=$course_id";
            $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                while( $row = mysqli_fetch_assoc($result) ){
                    $coursePic = $row["cover"];
                    $coursename = $row["name"];
                    $courseDescription = $row["description"];
                    $courseInstr = $row['instructorId'];
                    $coueseCategorie = $row['categorieId'];
                    $courseBookId = $row['bookId'];
            
                }
            }
        }else header('Location: courses.php?back=1');    

    }else header('Location: courses.php?back=1');

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

		<section id="page-title">

			<div class="container clearfix">
                <h1 style="padding-block: 20px; text-transform: capitalize;">Welcome <b><?php if(isset($loginName)) echo $loginName; ?></b></h1>			
            </div>

		</section>

		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

                    <div class="postcontent nobottommargin">

                        <?php

                            if(isset($image_err_msg) or isset($submit_message) or isset($book_error) ){
                                echo "<div class='alert alert-danger'>";
                                
                                    echo "Please fill the form carefully and correctly<br>";

                                    echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                                </div>";    

                            }

                        ?>
                    
                        <h3>Update Course</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nameID" class="custom_label">Course Name</label>
                                <input type="text" id="nameID" placeholder="Full Name" value="<?php if(isset($coursename)) echo $coursename; ?>" name="course_name" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+">
                            </div>


                            <div class="form-group">                    
                                <label class="custom_label"> Book Selection</label>
                                <select class="form-control custom_input"  name="book_op">
                                    <?php 
                                        $query = "SELECT * FROM `library`";
                                        $result = mysqli_query($connection, $query);

                                        if(mysqli_num_rows($result) > 0){
                                            while( $row = mysqli_fetch_assoc($result) ){?>

                                    <option <?php if($row['id'] == $courseBookId) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['name']; ?>  </option>

                                    <?php       
                                            } 
                                        }
                                    ?>
                                    </select>
                                    <?php if(isset($book_error)) echo $book_error; ?>
                            </div>


                            <div class="form-group">                    
                                <label class="custom_label"> Categorie Selection</label>
                                <select class="form-control custom_input"  name="categorie_op">
                                    <?php 
                                        $query = "SELECT * FROM categories";
                                        $result = mysqli_query($connection, $query);

                                        if(mysqli_num_rows($result) > 0){
                                            while( $row = mysqli_fetch_assoc($result) ){?>

                                    <option <?php if($row['id'] == $coueseCategorie) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['categorie']; ?>  </option>

                                    <?php       
                                            } 
                                        }
                                    ?>

                                </select>
                                <?php if(isset($categorie_error)) echo $categorie_error; ?>
                            </div>

                            <div class="form-group">                    
                                <label class="custom_label">Instructor Selection</label>
                                <select class="form-control custom_input"  name="ins_op">
                                    <?php 
                                        $query = "SELECT * FROM instructor";
                                        $result = mysqli_query($connection, $query);

                                        if(mysqli_num_rows($result) > 0){
                        
                                            while( $row = mysqli_fetch_assoc($result) ){?>

                                    <option <?php if($row['id'] == $courseInstr) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['name']; ?>  </option>

                                    <?php       
                                            } 
                                        }
                                    ?>

                                </select>
                                <?php if(isset($instructor_error)) echo $instructor_error; ?>
                            </div>

                            <div class="form-group">
                                <img src="images/courses/<?php if(isset($coursePic)) echo $coursePic; ?>" width="100px" height="100px" style="object-fit: cover;">   
                            </div>

                            <div class="form-group">
                                <label class="btn bg_slate" style="font-size: 11px;" for="my-file-selector">
                                    <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                                Change Cover Picture
                                </label>
                                <span class='label label-success' id="upload-file-info"></span>
                                <?php if(isset($image_err_msg)){ echo $image_err_msg; } ?>
                            </div>
                            <input type="hidden" value="<?php if(isset($coursePic)) echo $coursePic; ?>" name="picValue" />

                            <div class="form-group">
                                <label for="descriptionId1" class="custom_label">Description</label>
                                <textarea id="descriptionId1" class="form-control custom_input" 
                                name="description"><?php if(isset($courseDescription)) echo $courseDescription; ?></textarea>
                            </div>

                            <div class="form-group">
                                <button name="submit" class="btn btn-block bg_slate" type="submit">Submit</button>
                            </div>
                        </form>
                            
        
                    </div>

				</div>

			</div>

		</section>

<?php include('footer.php'); 

}

?>