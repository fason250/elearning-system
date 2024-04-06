<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

    header('Location: index.php');
} else {

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];
    $message = "";


    if(isset($_POST['submit']) ){

        if($is_admin == 'yes'){

            if( isset($_POST['fullname']) && !empty($_POST['fullname'])){

                $name = mysqli_real_escape_string($connection,$_POST['fullname']);
            }


            if(isset($_POST["categorie_op"]) && !empty($_POST["categorie_op"])){

                $categorie_option = $_POST["categorie_op"];
            }

            
            if( isset($_POST['description']) && !empty($_POST['description']) ){
                
                $description = mysqli_real_escape_string($connection,$_POST['description']);   
            }  


            if(isset($_FILES["book_file"]["name"]) && !empty($_FILES["book_file"]["name"])){

                $valid_extension = "pdf";
                $temp = explode(".", $_FILES["book_file"]["name"]);
                $extension = end($temp);
                if(($_FILES["book_file"]["type"] == "application/pdf") && $extension == $valid_extension ){
                    if($_FILES["book_file"]["error"] > 0){
                        $file_error = "please try again with valid file";
                    }else{
                        $target_dir = "books/";    
                        $fileName = $_FILES["book_file"]["name"]; 
                        $fileTmpLoc = $_FILES["book_file"]["tmp_name"]; 
                        $fileType = $_FILES["book_file"]["type"];
                        $fileSize =$_FILES["book_file"]["size"]; 
                       

                        $temp = explode(".", $_FILES["book_file"]["name"]);
                         $newfilename = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                        if (move_uploaded_file($_FILES["book_file"]["tmp_name"], $target_dir . $newfilename)) {
                            
                        } else {
                            $file_error =  '<b class="text-danger">Sorry, there was an error uploading your file';
                        }
                        
                    } 
                }else{
                    $file_error = '<b class="text-danger">File is not PDF.</b>';   
                }
            }


            if( isset($_FILES["profilePic"]["name"]) && !empty($_FILES["profilePic"]["name"]) ){
                $target_dir = "images/library/";
                $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
                $upload_status = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                $check = getimagesize($_FILES["profilePic"]["tmp_name"]);

                if($check !== false) {
                    
                    $upload_status = 1;
                } else {
                    $message_picture  = '<b class="text-danger">File is not an image</b>';
                    $upload_status = 0;
                }
            
                if ($_FILES["profilePic"]["size"] > 50000000) {
                    $message_picture =  '<b class="text-danger">Sorry, your file is too large.</b>';
                    $upload_status = 0;
                }
                
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    $message_picture =  '<b class="text-danger">Sorry, only JPG, JPEG, PNG & GIF files are allowed</b>';
                    $upload_status = 0;
                }
                
                if ($upload_status != 0) {
                    $temp = explode(".", $_FILES["profilePic"]["name"]);
                    $newfilename1 = mysqli_real_escape_string($connection,round(microtime(true)) . '.' . end($temp));
                    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_dir . $newfilename1)) {
                        
                    } else {
                        $message_picture =  '<b class="text-danger">Sorry, there was an error uploading your file';
                    }
                }
            }else{
                $message_picture =  '<b class="text-danger">Please Select Your Profile picture</b>';
            } 



            if( ( isset($name) && !empty($name) )  && ( isset($newfilename) && !empty($newfilename) ) && (isset($categorie_option) && !empty($categorie_option)) && ( isset($description) && !empty($description) ) && ( isset($newfilename1) && !empty($newfilename1) ) ){

                $insert_query = "INSERT INTO library (name, categorieId, description, book, image) VALUES ('$name', $categorie_option, '$description','$newfilename','$newfilename1')";

                if(mysqli_query($connection, $insert_query)){                        
                   
                    header('Location: library.php#end');
                }else{
                    $submit_message = '<div class="alert alert-danger">
                        <span>please try again unable to submit</span>
                    </div>';
                }    
            }else{
                 $submit_message = '<div class="alert alert-danger">
                <span>please try again unable to submit</span>
            </div>';
            }
        }else{

             $message = "<div class='alert alert-danger'> 
                <p>you Are not allowed to perform that operation</p><br>       
                <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
                </div>";    
        }

    }



if(isset($_GET['success'])){
    $message = "<div class='alert alert-success'> 
    <p>Record Delted successfully.</p><br>       
    <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
    </div>";
}

if(isset($_GET['delete_id'])){ 

    $delBook = $_GET['delete_id'];

    if($is_admin == 'yes'){
       
                       
        $message = "<div class='alert alert-danger'> 
            <p>Are you sure want to delete this Record!</p><br>
                <form action='{$_SERVER['PHP_SELF']}?id={$delBook}' method='post'>
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
    <p>you are not allowed to perform these operations</p><br>       
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

    $idBook = $_GET['id'];
    $query2 = "SELECT * FROM library WHERE id='$idBook' ";
    $result2 = mysqli_query($connection, $query2);

    if(mysqli_num_rows($result2) > 0){
    
         while( $row2 = mysqli_fetch_assoc($result2) ){

            unlink("images/library/{$row2['image']}");
            unlink("books/{$row2['book']}");
         }
    }

    $query = "DELETE FROM library WHERE id=$idBook";
    $result = mysqli_query($connection,$query);
    
    if($result){
        
        header("Location: library.php?success=1");
    } else {
        echo "Error on deleting the record please try again";
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

						<li class="current"><a href="library.php"><i class="icon-line-align-center"></i>Library</a></li>

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

                        if(isset($file_error) or isset($submit_message) or isset($message_picture)){
                            echo "<div class='alert alert-danger'>";
                            
                            echo "Please fill the form carefully and correctly<br>";
                            
                            echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                            </div>";    

                        }

                 ?>
                 
					<h3>Add new Book</h3>

                    <form action="" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="fullnameId" class="custom_label">Book Name</label>
                        <input type="text" id="fullnameId" placeholder="Full Name" name="fullname" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+" required>
                    </div>
                 
                    <div class="form-group">
                        <label class="btn bg_slate" style="font-size: 11px;" for="my-file-selector">
                            <input id="my-file-selector" name="profilePic" type="file" style="display:none;" onchange="$('#upload-file-info1').html($(this).val());" required>
                            Cover Photo
                        </label>
                        <span class='label bg_slate' id="upload-file-info1"></span>
                        <?php if(isset($message_picture)){ echo $message_picture; } ?>
                    </div>

                        <div class="form-group">                    
                            <label class="custom_label">Categorie Selection</label>
                            <select class="form-control custom_input"  name="categorie_op" required>
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
                                <?php if(isset($categorie_error)) echo $categorie_error; ?>
                            </div>

                            <div class="form-group">
                                <label class="btn bg_slate" style="font-size: 11px;" for="my-file-selector1">
                                    <input id="my-file-selector1"  name="book_file" type="file" style="display:none;" onchange="$('#upload-file-info').html($(this).val());" required>
                                    Upload Book
                                </label>
                                <span class='label bg_slate' id="upload-file-info"></span>
                                <?php if(isset($file_error)){ echo $file_error; } ?>
                                
                            </div>
                            <div class="form-group">
                                <label for="descriptionId" class="custom_label">Description</label>
                                <textarea id="descriptionId" class="form-control custom_input" 
                                name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <button name="submit" class="btn btn-block bg_slate" onclick="uploadFile()" type="submit">Submit</button>
                            </div>
                        </form>
                            
    
                        <table class="table table-striped table-bordered">
                        <tr style="background-color: darkslategray;color:white;">
                            <th>ID</th>
                            <th>Cover</th>
                            <th>View</th>
                            <th>Book</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php

                            $query = "SELECT * FROM library";

                            $result = mysqli_query($connection, $query);

                            if(mysqli_num_rows($result) > 0){
        
                            while( $row = mysqli_fetch_assoc($result) ){
                                    echo "<tr>";
                                    echo "<td>".$row["id"]."</td>";

                                    echo "<td><img src=images/library/".$row["image"]." style='object-fit: cover;' width='80px' height='80px'></td>";
                                    
                                    echo '<td><a href="view.php?libId='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                                    <span class="icon-eye-open"></span></a></td>';             

                                    echo '<td><a href="books/book.php?name='.$row['book']. '" type= "button" class="btn bg_slate btn-sm">
                                    <span class="icon-eye-open"></span></a></td>';

                                    echo '<td><a href="updatelibrary.php?id='.$row['id']. '" type= "button" class="btn bg_slate btn-sm">
                                    <span class="icon-edit"></span></a></td>';
                                    
                                    echo '<td><a href="library.php?delete_id='.$row['id']. '" type= "button" class="btn btn-danger btn-sm">
                                    <span class="icon-trash2"></span></a></td>';

                                    echo "<tr>";  
                                }
                        } else {
                            echo "<div class='alert alert-danger'>You have no Record<a class='close' data-dismiss='alert'>&times</a></div>";
                        }
                        
                        
                            mysqli_close($connection);
                        ?>

                        <tr>
                            <td colspan="6" id="end"><div class="text-center"><a href="library.php" type="button" class="btn btn-sm bg_slate"><span class="icon-plus"></span></a></div></td>
                        </tr>
                    </table>

  
					</div>


				</div>

			</div>

		</section>

<?php include('footer.php'); 
}

?>
