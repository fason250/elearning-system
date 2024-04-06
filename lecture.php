
<?php 
	
	if(isset($_GET['id'])){

		$courseId = $_GET['id'];

	} else {
		header("Location: course.php");
	}


include("header.php"); ?>

		<div id="page-menu">

			<div id="page-menu-wrap">


			</div>

		</div>


		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

 

				<?php

					if(isset($courseId)){
						$query = "SELECT * FROM course WHERE id='$courseId'";

        				$result = mysqli_query($connection, $query);

       					if(mysqli_num_rows($result) > 0){
        
         					while( $row = mysqli_fetch_assoc($result) ){

         					 	$teacherId = $row['instructorId'];
         					 	$courseName = $row['name'];
         					 	$book =$row['bookId'];

         					} 

         				} else{
         					echo '<script>
           						window.location = "course.php";
      						</script>';
         				}
					}

					if(isset($teacherId)){

						$query = "SELECT * FROM instructor WHERE id='$teacherId'";

        				$result = mysqli_query($connection, $query);

       					if(mysqli_num_rows($result) > 0){
        
         					while( $row = mysqli_fetch_assoc($result) ){

         					 	$teacherName = $row['name'];
         					 	$teacherMail = $row['mail'];
         					 	$teacherPic = $row['image'];
         					 	$teacherQ = $row['qualification'];
         					 	$teacherDes = $row['description'];

         					} 

         				}else{
         					echo '<script type="text/javascript">
           						window.location = "course.php"
      						</script>';
         				}

					}
					

				?>

				<div class="nobottommargin clearfix">

				<div class="row">	
					<div class="">
						<div class="col-xs-6 col-sm-3">		
    						<div class="thumbnail">
      							<img src="admin/images/instructor/<?php echo $teacherPic; ?>" alt="Instructor">
      								<div class="caption">
        							<h3><?php echo $teacherName; ?></h3>
        							
      							</div>
    						</div>
  						
						</div>

				
						<div class="col-md-9">
							<div class="panel panel-default">
  
  								<div class="panel-heading">About</div>
  								<div class="panel-body">
    								<p><?php echo $teacherDes; ?></p>
  								</div>

  								<table class="table table-bordered">
 						  		 <tr>
 						  		 	<th class="active" style="width: 50px;">Qualification</th>
 						  		 	<td><?php echo $teacherQ; ?></td>
 						  		 </tr>

 						  		 <tr>
 						  		 	<th class="active" style="width: 50px;">Email</th>
 						  		 	<td><?php echo $teacherMail; ?></td>
 						  		 </tr>

 						  		 <tr>
 						  		 	<th class="active" style="width: 50px;">Book</th>
 						  		 	
 						  		 	<td><a href="courselibrary.php?BookId=<?php echo $book; ?>" type= "button" class="btn btn-sm" style="background-color: darkslategray;">
                					<span class="icon-eye-open"></span></a></td>
 						  		 </tr>

  								</table>
					
							</div>
						</div>
					</div>	
				</div>

				<div class="section notopmargin notopborder">
					<div class="container clearfix">
						<div class="heading-block center nomargin">
							<h3><?php echo $courseName; ?></h3>
						</div>
					</div>
				</div>

				<?php

if(isset($courseId)){

	$query = "SELECT * FROM `content` WHERE courseId='$courseId'";

	$result = mysqli_query($connection, $query);

	   if(mysqli_num_rows($result) > 0){
		 while( $row = mysqli_fetch_assoc($result) ){

		echo '<div style="height: 70px; " class="well well-sm"><h3><strong><a style="color: #000;" href="content.php?id='.$row["id"].'"> <i class="icon-chevron-sign-right"></i>'.$row["lectureName"].'</a></strong></h3></div>';
	} 
}else{
echo "<h3 class='center'>Sorry, Content not available</h3>";
}
}
?>


<?php

if(isset($_GET['id'])){

$content_Id = $_GET['id'];

$query = "SELECT * FROM `content` WHERE id= '$content_Id'";

$result = mysqli_query($connection, $query);

if(mysqli_num_rows($result) > 0){

	 while( $row = mysqli_fetch_assoc($result) ){
		
		$lecureContent = $row['content'];
		$lectureName = $row['lectureName'];

	}

}else {

	echo '<h1>No Content Found..!</h1>';

		$lecureContent = " ";
		$lectureName = " ";


   } 
}else{
echo '<script type="text/javascript">
  window.location("course.php")
</script>';
}

?>

</div>
</div>
</div>
</section>

<?php include("footer.php"); ?>