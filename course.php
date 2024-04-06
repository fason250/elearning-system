<?php include("header.php"); ?>

		<section id="page-title" style="border-bottom: 1px solid darkslategray;">

			<div class="container clearfix">
				<h3>Some courses are <strong>Comming Soon...!</strong></h3>
				<span>Intellistudy Courses</span>
			</div>

		</section>
	

		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<div class=" bottommargin clearfix ">

						<div class="row">
						<?php				        
		
		$query = "SELECT * FROM course";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
         while($row = mysqli_fetch_assoc($result) ){
         		$courseId = $row["id"];
         	 	$coursePic = $row["cover"];
                $coursename = $row["name"];
                $courseDescription = $row["description"];

                echo '<div class="col-sm-6 col-md-3">
							<div class="thumbnail image_fade">
							  <img data-src="holder.js/300x200" alt="Image" src="admin/images/courses/'.$coursePic.'" style="display: block; border: 2px solid #555; object-fit: cover; height: 200px;width:100%;">
							  <div class="caption">
							  	
								<h5>'.$coursename.'</h5>
								<p  styl="overflow: auto;">'.$courseDescription.'</p>
								<a href="lecture.php?id='.$courseId.'" class="btn btn-lg btn-block" style="background-color: darkslategray;color:white;" role="button"><strong>Go To Course</strong></a>
							  </div>
							</div>
						  </div>';
         }
     }else{echo '<div class="section notopmargin notopborder">
					<div class="container clearfix">
						<div class="heading-block center nomargin">
							<h3>Courses are not available Yet</h3>
							</div>
						</div>
					</div>';}
?>
	
						  
					</div>
				</div>		
			</div>
		</div>
	</section>

<?php include("footer.php"); ?>