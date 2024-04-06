<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {

    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];
    $message = " ";


    if(isset($_POST['submit']) ){

        if($is_admin == 'yes'){ 
            if(isset($_POST["course_op"]) and !empty($_POST["course_op"])){

                $course_option = $_POST["course_op"];
            }

        
            if( isset($_POST['editor']) and !empty($_POST['editor']) ){
                    
                $lecture_content = $_POST['editor'];
            }else{
                $content_err_msg = "<b class='text-danger'>please add some content</b>";
            }    


            if(isset($_POST['lecture_name']) and !empty($_POST['lecture_name'])){
                    
                $lecture_name = mysqli_real_escape_string($connection,$_POST['lecture_name']);
            }


            if((isset($lecture_name) and !empty($lecture_name)) and ( isset($course_option) and !empty($course_option) ) and ( isset($lecture_content) and !empty($lecture_content) ) ){

                $insert_query = "INSERT INTO content(content,courseId,lectureName) VALUES ('$lecture_content',$course_option,'$lecture_name')";

                if(mysqli_query($connection, $insert_query)){
                    header('Location: content.php#end');
                }else{
                    $submit_err_msg = '<div class="alert alert-danger">
                            <span>failed to add a content please try again later!</span>
                        </div>';
                }
            } 


    }else{
    
         $message = "<div class='alert alert-danger'> 
            <p> you are not allowed to perform these action!!</p><br>       
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

        $content_id = $_GET['delete_id'];

        if($is_admin == 'yes'){
                           
            $message = "<div class='alert alert-danger'> 
             <p>Are you sure want to delete this Record? No take baacks!</p><br>
                    <form action='{$_SERVER['PHP_SELF']}?id={$content_id}' method='post'>
                       <input type='submit' class='btn btn-danger btn-sm'
                       name='confirm_delete' value='Yes' delete!>
                       <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>No thanks!</a>                    
                    </form>
        
            </div>";
    }else{
        $message = "<div class='alert alert-danger'> 
        <p>you are not allowed to peform these operation</p><br>       
        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
        </div>";
    }
}


if(isset($_GET['back'])){

    $back = $_GET['back'];

    if($back!=2){
            $update_status = "<div class='alert alert-danger'> 
        <p>you are not allowed to perform these operations!</p><br>       
        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
    </div>";
    }else{

        $update_status = "<div class='alert alert-success'> 
            <p>Update successfully.</p><br>       
            <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
    </div>";
    }

} 


if(isset($_POST['confirm_delete'])){

    $id = $_GET['id'];
    $query = "DELETE FROM content WHERE id=$id";
    $result = mysqli_query($connection,$query);
    
    if($result){
        header("Location: content.php?success=1");
    } else {
        echo "Error".$query."<br>".mysqli_error($conn);
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

                            echo $message; 
                            if(isset($update_status)) echo $update_status;

                        ?>
                    
                        <h3>Insert Course Content</h3>

                        <form action="" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="nameId1" class="custom_label">Lecture Name</label>
                                <input type="text" id="nameId1" placeholder="Lecture Name" name="lecture_name" class="form-control custom_input" title="Only lower and upper case and space allowed" pattern="[A-Za-z/\s]+" required>
                            </div>

                            <div class="form-group">                    
                                <label class="custom_label">Course Selection</label>
                                <select class="form-control custom_input"  name="course_op" required>
                                <?php 
                                    
                                    $query = "SELECT * FROM course";
                                    $result = mysqli_query($connection, $query);

                                    if(mysqli_num_rows($result) > 0){
                                        while( $row = mysqli_fetch_assoc($result) ){?>

                                    <option value="">select course category</option>
                                    <option value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?></option>

                                <?php       
                                        } 
                                    }
                                ?>
                                </select>
                            </div>
                            
                            <textarea class="ckeditor" name="editor" ></textarea>
                            <?php if(isset($content_err_msg)) echo $content_err_msg; ?>
                            
                        <div class="form-group">
                            <button name="submit" class="btn btn-block bg_slate" type="submit">Submit</button>
                        </div>
                            </form>
                                
                            <table class="table table-striped table-bordered">
                            <tr style="background-color: darkslategray;color:white;">
                                <th>ID</th>
                                <th>Content</th>
                                <th>Lecture Name</th>
                                <th>view course</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            <?php

                                $query = "SELECT * FROM content";
                                $result = mysqli_query($connection, $query);

                                if(mysqli_num_rows($result) > 0){
                                    while( $row = mysqli_fetch_assoc($result) ){

                                        $content_id = $row['courseId'];
                                        $query2 = "SELECT * FROM course WHERE id =$content_id ";
                                        $result2 = mysqli_query($connection, $query2);

                                            if(mysqli_num_rows($result2) > 0){
                                                while( $row2 = mysqli_fetch_assoc($result2) ){

                                                    $courseName = $row2['name']; 
                                                }
                                            }else{
                                                $courseName='Insert Course Name';
                                            }

                                            echo "<tr>";
                                                
                                                echo "<td>{$row["id"]}</td>";


                                                echo "<td>".$row["lectureName"]."</td>"; 

                                                echo "<td>{$courseName}</td>";
                                                
                                                echo '<td><a href="view.php?id='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                                                <span class="icon-eye-open"></span></a></td>';

                                                echo '<td><a href="updatecontent.php?id='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                                                <span class="icon-edit"></span></a></td>';
                                                
                                                echo '<td><a href="content.php?delete_id='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                                                <span class="icon-trash2"></span></a></td>';

                                            echo "<tr>";  
                                    }
                                }else {
                                    echo "<div class='alert alert-danger'>You have no Content yet!!<a class='close' data-dismiss='alert'>&times</a></div>";
                                }
                            
                            
                                mysqli_close($connection);
                            ?>

                            <tr>
                                <td colspan="6" id="end"><div class="text-center"><a href="content.php" type="button" class="btn btn-sm bg_slate"><span class="icon-plus"></span></a></div></td>
                            </tr>
                        </table>

                    </div>
				</div>
			</div>
		</section>
        <script src="./ckeditor/ckeditor.js" type="text/javascript"></script>

<?php include('footer.php'); 

}

?>

