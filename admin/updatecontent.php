
<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $contentId = $_GET['id'];
    $is_admin = $_SESSION['accountType'];


    if(isset($_POST['submit']) ){

            if(isset($_POST["course_op"]) && !empty($_POST["course_op"])){
                $course_option = $_POST["course_op"];

            }
        
            
            if( isset($_POST['editor']) && !empty($_POST['editor']) ){
                    
                $lectureContent = $_POST['editor'];
            }  

            
            if( isset($_POST['name']) && !empty($_POST['name'])){
                $lectureName = mysqli_real_escape_string($connection,$_POST['name']);
            }


            if((isset($lectureName) && !empty($lectureName)) && (isset($course_option) && !empty($course_option)) && (isset($lectureContent) && !empty($lectureContent))){

                $insert_query = "UPDATE content SET content = '$lectureContent', courseId = '$course_option', lectureName = '$lectureName' WHERE id= $contentId";

                if(mysqli_query($connection, $insert_query)){
                                           
                    header('Location: content.php?back=2');
                }else{
                    $update_err_msg = '<div class="alert alert-danger">
                       <span>Failed to update the record!!</span>
                    </div>';
                }
            }
        }



if(isset($_GET['id'])){

    $contentId = $_GET['id'];
    if($is_admin == 'yes') {

       $query = "SELECT * FROM content WHERE id=$contentId ";
        $result = mysqli_query($connection,$query);

        if(mysqli_num_rows($result) > 0){
            while( $row = mysqli_fetch_assoc($result) ){
                $content_Name = $row["lectureName"];
                $previous_content = $row["content"];
                $course_Id = $row["courseId"];
            }
        }
    }else header('Location: content.php?back=1');    

}else header('Location: content.php?back=1');

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

						<li  class="current"><a href="content.php"><i class="icon-line-content-left"></i>Content</a> </li>

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

                            if(isset($update_status)) echo $update_status;

                                if(isset($message_name) or isset($update_err_msg) or isset($message_Content) or isset($course_error)  ){
                                    echo "<div class='alert alert-danger'>";
                                    
                                    echo "Please fill the form carefully and correctly<br>";

                                    echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                                    </div>";    

                                }

                        ?>
                
                        <h3>Update Course Content</h3>

                        <form action="" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="nameId1" class="custom_label">Lecture Name</label>
                                <input type="text" id="nameId1" value="<?php if(isset($content_Name)) echo $content_Name; ?>" placeholder="Lecture Name" name="name" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+">
                            </div>

                            <div class="form-group">                    
                                <label for="contentsel" class="custom_label">select course</label>
                                <select class="form-control custom_input"  name="course_op" id="contentsel">
                                    <?php 
                                        
                                        $query = "SELECT * FROM course";

                                        $result = mysqli_query($connection, $query);

                                        if(mysqli_num_rows($result) > 0){
                                        
                                            while( $row = mysqli_fetch_assoc($result) ){?>
                                
                                    <option <?php if($row['id'] == $course_Id) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?>  </option>

                                    <?php       
                                            } 
                                        }
                                    ?>

                                </select>
                            </div>
                    
                            <textarea class="ckeditor" name="editor"><?php  echo $previous_content; ?></textarea>
                        
                            <div class="form-group">
                                <button name="submit" class="btn btn-block bg_slate" type="submit">update content</button>
                            </div>
                        </form>
                    </div>
				</div>
			</div>
		</section>
<script src="ckeditor/ckeditor.js" type="text/javascript"></script>

<?php include('footer.php'); 

}

?>