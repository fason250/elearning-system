<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {
    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];

    if( isset($_POST['submit']) ){

        if( isset($_POST['title']) and !empty($_POST['title'])){
            
            $title = mysqli_real_escape_string($connection,$_POST['title']);
        }


        if(isset($_POST["contentsel"]) and !empty($_POST["contentsel"])){
            $option = $_POST["contentsel"];

        }

        if( isset($_POST['content']) and !empty($_POST['content']) ){
            
            $content = mysqli_real_escape_string($connection,$_POST['content']);
        }

        
        if( (isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"])) or (isset($_POST["link"]) and !empty($_POST["link"]))){

            if(isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"])){

                $image_folder = "images/blog/";
                $image_file = $image_folder . basename($_FILES["profilePic"]["name"]);
                $upload_status = 1;
                $image_file_type = pathinfo($image_file,PATHINFO_EXTENSION);
                $check_image = getimagesize($_FILES["profilePic"]["tmp_name"]);
                
                if($check_image !== false) {    
                    $upload_status = 1;
                }else {
                    $picture_err_msg  = '<b class="text-danger">File is not an image</b>';
                    $upload_status = 0;
                }

    
                if ($_FILES["profilePic"]["size"] > 5000000) {
                    $picture_err_msg =  '<b class="text-danger">Sorry, your file is too large</b>';
                    $upload_status = 0;
                }
                
                if($image_file_type != "jpg" and $image_file_type != "png" and $image_file_type != "jpeg" and $image_file_type != "gif" ) {
                    $picture_err_msg =  '<b class="text-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed</b>';
                    $upload_status = 0;
                }
                
                if ($upload_status != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename)) {
                        echo "<script>console.log('file moved successfully')</script>";
                    } else {
                        $picture_err_msg =  '<b class="text-danger">Sorry, there was an error uploading your file';
                    }
                }

            }else{ 
                $newfilename = $_POST["link"];
            }

        }else{ 

            $picture_err_msg =  '<b class="text-danger">Please insert Picture OR video Link</b>';
        }

         
        $post_date = date("F d, Y");

    
        if( ( isset($title) and !empty($title) ) and ( isset($newfilename) and !empty($newfilename) ) ){

            $insert_query = "INSERT INTO blog(postContent, postDate, admin, title, status, post) VALUES ('$content','$post_date','$loginId','$title','$option','$newfilename')";

                if(mysqli_query($connection, $insert_query)){
                   
                    header('Location: blog.php#end');
                }else{
                    $ddsubmit_err_msg = '<div class="alert alert-danger">
                        <span>failed to submit data try again later!</span>
                    </div>';
                }
        }

    }

$message = " ";

if(isset($_GET['success'])){
    $message = "<div class='alert alert-success'> 
    <p>Record Deleted successfully.</p><br>       
    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
    </div>";
}    

if(isset($_GET['delete_id'])){ 

    $delpost = $_GET['delete_id'];
    $deladmin = $_GET['admin'];

    if($is_admin == 'yes' or $deladmin == $loginId){
                       
        $message = "<div class='alert alert-danger'> 
                    <p>Are you sure want to delete this Admin?</p><br>
                    <form action='{$_SERVER['PHP_SELF']}?id={$delpost}' method='post'>
                    <input type='submit' class='btn btn-danger btn-sm'
                   name='confirm_delete' value='Yes' delete!>
                    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Oops, no thanks!</a>
                </form>
        </div>";
    
    }else{
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
    <p>you are not allowed to perform these operation </p><br>       
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
    $query2 = "SELECT * FROM blog WHERE id=$id ";
    $result2 = mysqli_query($connection, $query2);

    if(mysqli_num_rows($result2) > 0){

         
        while($row2 = mysqli_fetch_assoc($result2)){
                
            if($row2['status'] == 'image'){
                unlink("images/blog/{$row2['post']}");
            }
        }
    }

 
    $query = "DELETE FROM blog WHERE id=$id";
    $result = mysqli_query($connection,$query);
    
    if($result){
        header("Location: blog.php?success=1");
    } else {
        echo "Error occured ";
        
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

						<li class="current"><a href="blog.php"><i class="icon-blogger"></i>Blog</a></li>

						<li><a href="library.php"><i class="icon-line-align-center"></i>Library</a></li>

						<li><a href="instructors.php"><i class="icon-guest"></i>Instructors</a></li>

                        <li><a href="team.php"><i class="icon-users"></i>Team</a></li>

                        <li><a href="logout.php"><i class="icon-line-power"></i>Logout</a></li>    

					</ul>
				</nav>

			</div>
		</div>

				
		<section id="page-title" style="border-bottom:1px solid darkslategray;">
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

                                if(isset($message_title) or isset($message_option) or isset($picture_err_msg) or isset($ddsubmit_err_msg) or isset($message_con) ){
                                    echo "<div class='alert alert-danger'>";
                                
                                    echo "Please fill the form carefully and correctly<br>";

                                    echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                                    </div>";    

                                }

                        ?>
                    
                            <h3>Add Blog</h3>

                            <form action="" method="post" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label for="titleID1" class="custom_label">Title</label>
                                    <input type="text" id="titleID1" placeholder="Title" name="title" class="form-control custom_input" required>
                                    <?php if(isset($message_title)){ echo $message_title; } ?>
                                </div>

                            
                                <div class="form-group">                    
                                    <label class="custom_label">Post Selection</label>
                                    <select class="form-control custom_input"  name="contentsel" id="contentsel" onchange="showinput()">
                                        <option value="">Select blog type [video or image]</option>
                                        <?php 
                                            $options = array("video","image");
                                            foreach ($options as $option) { ?>
                                        <option value="<?php echo $option; ?>"> <?php echo $option; ?> </option>

                                        <?php       
                                            }
                                        ?>

                                    </select>
                                </div>
                                <?php if(isset($message_option)) echo $message_option; ?>

                                <div id="data"></div>
                        
                                <div class="form-group">
                                    <label for="contentID1" class="custom_label">Post Content</label>
                                    <textarea id="contentID1" class="form-control custom_input" name="content" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button name="submit" class="btn btn-block bg_slate" type="submit">Submit</button>
                                </div>
                            </form>
        
        
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <th>Post</th>
                                    <th>Title</th>
                                    <th>Post Content</th>
                                    <th>Post Date</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                <?php

                                    $query = "SELECT * FROM blog";

                                    $result = mysqli_query($connection, $query);

                                    if(mysqli_num_rows($result) > 0){

                                        while( $row = mysqli_fetch_assoc($result) ){
                                            echo "<tr>";
                                                echo "<td>{$row['id']}</td>"; 
                                                if($row["status"]=='image'){

                                                    echo "<td><img src='images/blog/{$row["post"]}' width='80px' height='80px' style='object-fit: cover;'></td>"; 

                                                }else{ ?>

                                                    <td width="80" height="80"><a target="_blank"><iframe width="80px" height="80px" src="https://www.youtube.com/embed/<?php echo $row['post']; ?>" title="play youtube video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></a>
                                                    </td>

                                                <?php }   

                                                        echo "<td>{$row["title"]}</td>  <td>{$row["postContent"]}</td>  <td>{$row["postDate"]}</td>";

                                                        echo "<td><a href='updateblog.php?id={$row['id']}&admin={$row['admin']}' type='button' class='btn bg_slate btn-sm'>
                                                        <span class='icon-edit'></span></a></td>";
                                                        
                                                        echo "<td><a href='blog.php?delete_id={$row['id']}&admin={$row['admin']}' type='button' class='btn btn-danger btn-sm'>
                                                        <span class='icon-trash2'></span></a></td>";

                                                    echo "</tr>";  
                                        }
                                    }else{
                                        echo "<div class='alert alert-danger'>You have no posts.<a class='close' data-dismiss='alert'>&times</a></div>";
                                    }
                                    
                            
                                        mysqli_close($connection);
                                    ?>

                                        <tr>
                                            <td colspan="7" id="end"><div class="text-center"><a href="blog.php" type="button" class="btn btn-sm bg_slate"><span class="icon-plus"></span></a></div></td>
                                        </tr>
                            </table>
        
                    </div>
				</div>
			</div>
		</section>

<script>

const showinput = ()=>{

    const selectOption = document.getElementById('contentsel')
    let blogType = selectOption.value

        if(blogType ===  'video'){
            document.getElementById('data').innerHTML =`<div class="form-group">
                                    <label for="linkID1" class="custom_label">Video Link</label>
                                    <input type="url" id="linkID1" placeholder="Link" required name="link" class="form-control custom_input">
                                </div>
                                <?php if(isset($picture_err_msg)){ echo $picture_err_msg; } ?>`;
        }else if(blogType === 'image'){
            document.getElementById('data').innerHTML = `<div class="form-group">
                                    <label class="btn bg_slate" for="my-file-selector">
                                    <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                                    Profile Picture
                                </label>
                                <span class='label bg_slate' id="upload-file-info"></span>
                                <?php if(isset($picture_err_msg)){ echo $picture_err_msg; } ?>
                            </div>`;
        }else{
            document.getElementById('data').innerHTML =  '';
        }

    } 
</script>


<?php include('footer.php'); 

}

?>