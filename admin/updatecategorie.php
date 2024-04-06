
<?php

  
 include("../include/config.php");

 if((!isset($_SESSION['userId']) && empty($_SESSION['userId'])) && (!isset($_SESSION['userName']) && empty($_SESSION['userName']))) {

	 header('Location: index.php');
 } else{

	 $categorie_id = $_GET["id"];
	 $loginName = $_SESSION['userName'];
	 $loginId = $_SESSION['userId'];
	 $is_admin = $_SESSION['accountType'];

	if( isset($_POST['submit']) ){
		 
		 if( isset($_POST['category_name']) && !empty($_POST['category_name'])){
	
			$category_name = mysqli_real_escape_string($connection,$_POST['category_name']);

				$update_query = "UPDATE categories SET
				 categorie = '$category_name'
				 WHERE id = $categorie_id ";

				if(mysqli_query($connection, $update_query)){	
					header('Location: categorie.php?back=2');

				}else{
					 $upadate_err_msg = '<div class="alert alert-danger">
						<span>failed to update</span>
					 </div>';
				}
		}
 	} 


	if(isset($_GET['id'])){

		$categorie_id = $_GET["id"];

		if($is_admin == 'yes') {

			$query = "SELECT * FROM categories WHERE id=$categorie_id ";
			$result = mysqli_query($connection,$query);

			if(mysqli_num_rows($result) > 0){
				while( $row = mysqli_fetch_assoc($result) ){

					$course_category_name = $row["categorie"];
							
				}
			}
		}else header('Location: categorie.php?back=1');    

	}else header('Location: categorie.php?back=1');
 
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

									if(isset($message_name) or  isset($upadate_err_msg)){
										echo "<div class='alert alert-danger'>";
										
											echo "Please fill the form carefully and correctly<br>";

											echo "<a type='button' class='btn bg_slate btn-sm' data-dismiss='alert'>Cancel</a>
										</div>";    

									}

							?>
						
							<h3>Update Course Categorie</h3>

							<form action="" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="CourseId1" class="custom_label">Course Category Name</label>
									<input type="text" id="CourseId1" placeholder="enter category name" value="<?php if(isset($course_category_name)) echo $course_category_name; ?>" name="category_name" class="form-control custom_input" title="Only lower and upper case and space allowed" pattern="[A-Za-z/\s]+">
								</div>
								
								<div class="form-group">
									<button name="submit" class="btn btn-block bg_slate" type="Submit">Submit</button>
								</div>
							</form>
								
			
					</div>
				</div>
			</div>
		</section>

<?php include('footer.php'); 

}

?>