
<?php

include("../include/config.php");

if((!isset($_SESSION['userId']) and empty($_SESSION['userId'])) and (!isset($_SESSION['userName']) and empty($_SESSION['userName']))) {
    
    header('Location: index.php');

}else{

    $loginName = $_SESSION['userName'];
    $loginId = $_SESSION['userId'];
    $is_admin = $_SESSION['accountType'];
    $message = " ";

    if( isset($_POST['submit']) ){

        if($is_admin == 'yes'){ 

            if( isset($_POST['fullname']) and !empty($_POST['fullname'])){

                $name = mysqli_real_escape_string($connection,$_POST['fullname']);
                $insert_query = "INSERT INTO categories(categorie) VALUES ('$name')";

                if(mysqli_query($connection, $insert_query)){ 
                    header('Location: categorie.php#end');

                }else{
                    $submit_err_msg = '<div class="alert alert-danger">
                        <span>failed to add category</span>
                    </div>';
                }
            }

        }else{

             $message = "<div class='alert alert-danger'> 
                <p>you allow not allowed to perfom that operation</p><br>       
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

        $category_id = $_GET['delete_id'];

        if($is_admin == 'yes'){
                        
            $message = "<div class='alert alert-danger'> 
                    <p>Are you sure want to delete this Record? No take baacks!</p><br>
                    <form action='{$_SERVER['PHP_SELF']}?id={$category_id}' method='post'>
                        <input type='submit' class='btn btn-danger btn-sm'
                        name='confirm_delete' value='Yes' delete!>
                        <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Oops, no thanks!</a> 
                    </form>
            </div>";
        }else{
            $message = "<div class='alert alert-danger'> 
            <p>you are not allowed to perform tha operation</p><br>       
            <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
            </div>";
        }
    }



    if(isset($_GET['back'])){

        $back = $_GET['back'];

        if($back!=2){
                $update_err_msg = "<div class='alert alert-danger'> 
                <p>you are not allowed to perform these operations</p><br>       
                <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
                </div>";
        }else{
            $update_err_msg = "<div class='alert alert-success'> 
                <p>Record Updated successfully</p><br>       
                <a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a> 
                </div>";
        }

    } 


    if(isset($_POST['confirm_delete'])){

        $id = $_GET['id'];

        if(mysqli_query($connection,"DELETE FROM categories WHERE id=$id")){
    
            header("Location: categorie.php?success=true");
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

                        <li  class="current"><a href="categorie.php"><i class="icon-book2"></i>Categories</a></li> 

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
                            if(isset($update_err_msg)) echo $update_err_msg;

                            if(isset($message_name) or isset($submit_err_msg)){
                                echo "<div class='alert alert-danger'>";
                                    echo "<span>Please fill the form carefully and correctly</span><br>";
                                    echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
                                </div>";    

                            }

                        ?>
                        
                        <h3>Add Course Category</h3>

                        <form action="" method="post" enctype="multipart/form-data">
                                
                                <div class="form-group">
                                    <label for="CourseId1" class="custom_label">Course Category</label>
                                    <input type="text" id="CourseId1" placeholder="course category name" name="fullname" class="form-control custom_input" title="Only lower and upper case and space" pattern="[A-Za-z/\s]+" required>
                                </div>
                                
                                <div class="form-group">
                                    <button name="submit" class="btn btn-block bg_slate" type="submit">Submit</button>
                                </div>
                            </form>
                                    
                    
                            
                            <table class="table table-striped table-bordered">
                                <tr style="background-color: darkslategray; color:white;">
                                    <th>ID</th>
                                    <th>Course Categories</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                <?php

                                    $query = "SELECT * FROM categories";
                                    $result = mysqli_query($connection, $query);

                                    if(mysqli_num_rows($result) > 0){

                                        while( $row = mysqli_fetch_assoc($result) ){
                                            echo "<tr>";
                                                echo "<td>{$row["id"]}</td> <td>{$row["categorie"]}</td>";
                                                echo "<td><a href='updatecategorie.php?id={$row['id']}' type= 'button' class='btn bg_slate btn-sm'>
                                                <span class='icon-edit'></span></a></td>";
                                                
                                                echo "<td><a href='categorie.php?delete_id={$row['id']}' type= 'button' class='btn btn-danger btn-sm'>
                                                <span class='icon-trash2'></span></a></td>";

                                            echo "</tr>";  
                                        }
                                    }else {
                                        echo "<div class='alert alert-danger'>You have no category yet<a class='close' data-dismiss='alert'>&times</a></div>";
                                    }
                            
                                mysqli_close($connection);
                                ?>

                                <tr>
                                    <td colspan="4" id="end"><div class="text-center"><a href="categorie.php" type="button" class="btn btn-sm bg_slate"><span class="icon-plus"></span></a></div></td>
                                </tr>
                            </table>

                    </div>
				</div>
			</div>
		</section>
<?php include('footer.php'); 

}
?>