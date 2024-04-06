
<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {

    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $postId = $_GET["id"];
    $is_admin = $_SESSION['accountType'];

    if( isset($_POST['submit']) ){

        $post_admin_id = $_POST['adminPost'];
       
        if( isset($_POST['title']) and !empty($_POST['title'])){
    
            $title = mysqli_real_escape_string($connection,$_POST['title']);  
        }

        if(isset($_POST["contentsel"]) and !empty($_POST["contentsel"])){

            $option = $_POST["contentsel"];
        } else {
            $option = $_POST['valueHide1'];
        }

        
        if( isset($_POST['content']) and !empty($_POST['content']) ){
            
            $content = mysqli_real_escape_string($connection,$_POST['content']);
        }

        if( (isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"])) or (isset($_POST["link"]) and !empty($_POST["link"]))){

            if(isset($_FILES["profilePic"]["name"]) and !empty($_FILES["profilePic"]["name"])){

                $image_folder = "images/blog/";
                $delete_file = 'yes';
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
        
                if($image_file_type != "jpg" and $image_file_type != "png" and $image_file_type != "jpeg" and $image_file_type != "gif" ) {
                    $image_err_msg =  '<b class="text-danger">Sorry only [JPG, JPEG, PNG & GIF] files are allowed</b>';
                    $upload_status = 0;
                }

                if ($upload_status != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if(move_uploaded_file($_FILES["profilePic"]["tmp_name"], $image_folder . $newfilename)) {
                        echo "<script> console.log('file moved successfully')</script>";
                    }else{
                        $image_err_msg =  '<b class="text-danger">Sorry, there was an error uploading your file';
                    }
                }

            }else{
                $newfilename = $_POST["link"];
            }

        }else{ 

            $newfilename =  $_POST['valueHide'];
            $delete_file = 'no';
        }

        $postDate = date("F d, Y");

        if(( isset($title) and !empty($title) ) and ( isset($newfilename) and !empty($newfilename) ) ){

            $insert_query = "UPDATE blog SET 
            postContent = '$content', 
            postDate = '$postDate', 
            admin = '$post_admin_id', 
            title = '$title', 
            status = '$option', 
            post = '$newfilename'
            WHERE id = $postId " ;

            if(mysqli_query($connection, $insert_query)){
               
                if($option == 'image'){    
                   if($delete_file == 'yes'){
                        unlink("images/blog/{$_POST['valueHide']}");
                    }
                }    
                header('Location: blog.php?back=2');
            }else{
                $submit_message = '<div class="alert alert-danger">
                    <span>Failed to submit data try again</span></div>';
            }
        } 
}


if(isset($_GET['id'])){

    $postId = $_GET["id"];
    $posted_admin = $_GET["admin"];

    if( $is_admin == 'yes' or $loginId == $posted_admin) {

        $query = "SELECT * FROM blog WHERE id='$postId' ";
        $result = mysqli_query($connection,$query);

            if(mysqli_num_rows($result) > 0){
                while( $row = mysqli_fetch_assoc($result) ){
                    $post_admin_id = $row['admin']; 
                    $status =  $row["status"];
                    $post = $row["post"];
                    $title = $row["title"]; 
                    $content = $row["postContent"];                            
                }
            }
    }else header('Location: blog.php?back=1');    

}else header('Location: blog.php?back=1');

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
    
                        if(isset($message_title) or isset($message_option) or isset($image_err_msg) or isset($submit_message) or isset($message_con) ){
                            echo "<div class='alert alert-danger'>";
                            

                            echo "Please fill the form carefully and correctly<br>";

                            echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                            </div>";    

                        }

                    ?>
                    
						<h3>Update Post</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                        
                    <div class="form-group">
                        <label for="titleId1" class="custom_label">Title</label>
                        <input type="text" id="titleId1" value="<?php echo $title; ?>" placeholder="Title" name="title" class="form-control custom_input" >
                        <?php if(isset($message_title)){ echo $message_title; } ?>
                    </div>

                   
                <div class="form-group">                    
                        <label for="contentsel" class="custom_label">Post Selection</label>
                        <select class="form-control custom_input"  name="contentsel" id="contentsel" onchange="showinput()">
                        <option value="">Select Option</option>
                    <?php 
                             $select = ["video","image"];
                             foreach ($select as $value) {
                    ?>

                    <option value="<?php echo $value; ?>" > <?php echo $value; ?>  </option>

                    <?php       
                        }
                    ?>

                </select>
            </div>
            <?php if(isset($message_option)) echo $message_option; ?>

                    <div id="data">
    

                    </div>
                   
                   <?php

                   if($status=='image'){ ?> 


                    <img src="images/blog/<?php echo $post; ?>" width="80px" height="80px">
                 <?php  }else{ ?>

                   <table><tr><td width="100px" height="100"><iframe width="80px" height="80px" src="https://www.youtube.com/embed/<?php echo $row['post']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></td></tr></table>

                  <?php }

                   ?> 
                   

                    <div class="form-group">
                        <label for="contentId1" class="custom_label">Post Content</label>
                        <textarea id="contentId1" class="form-control custom_input" 
                         name="content"><?php echo $content; ?></textarea>
                    </div>
                     <?php if(isset($message_con)) echo $message_con; ?>   

                    <input type="hidden" value="<?php if(isset($status)) echo $status; ?>" name="valueHide1" />                    
                    <input type="hidden" value="<?php if(isset($post_admin_id)) echo $post_admin_id; ?>" name="adminPost" />
                    <input type="hidden" value="<?php if(isset($post)) echo $post; ?>" name="valueHide" />

                     
                    <div class="form-group">
                        <button name="submit" class="btn btn-block bg_slate" type="submit">Submit</button>
                    </div>
                </form>
                        
				
					</div>


				</div>

			</div>

		</section>


<script>
const showinput = () =>{
    const selectOption = document.getElementById('contentsel');
    let blogType = selectOption.value;
        if(blogType ==='video')
        {
            document.getElementById('data').innerHTML =  
            `<div class="form-group">
                        <label for="link" class="custom_label">Video Link</label>
                        <input type="text" placeholder="Link" name="link" class="form-control custom_input">
                    </div>
                    <?php if(isset($image_err_msg)){ echo $image_err_msg; } ?>`;
        }else if(blogType === 'image'){
            document.getElementById('data').innerHTML =  
            `<div class="form-group">
                        <label class="btn bg_slate" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());">
                            Profile Picture
                        </label>
                        <span class='label bg_slate' id="upload-file-info"></span>
                        <?php if(isset($image_err_msg)){ echo $image_err_msg; } ?>
                    </div>`;
        } else {
            document.getElementById('data').innerHTML =  
            ``;
        }

    } 
</script>




<?php include('footer.php'); 

}

?>


