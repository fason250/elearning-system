<?php include("header.php"); ?>


		<section id="page-title" style="border-bottom: 1px soolid darkslategray;">

			<div class="container clearfix">
				<h1>Team</h1>
				<span>Intellistudy Team</span>
			</div>

		</section>

	

		
		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">


					<div class="clear"></div>

					<div class="fancy-title title-border title-center">
						<h3>Team Members</h3>
					</div>

					<div id="oc-team" class="owl-carousel team-carousel bottommargin carousel-widget" data-margin="30" data-pagi="false" data-items-xs="2" data-items-sm="2" data-items-lg="4">
					<?php 
        $query = "SELECT * FROM team";

        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0){
        
         while( $row = mysqli_fetch_assoc($result) ){

         		$memberPic = $row['image'];
         		$memberName = $row['name'];
         		$memberQ = $row['qualification'];


         		echo '<div class="oc-item">
							<div class="team">
								<div class="team-image">
									<img src="admin/images/team/'.$memberPic.'" alt="intelistudy member profile" style="height:250px; object-fit:cover;">
								</div>
								<div class="team-desc">
									<div class="team-title"><h4 style="color: darkslategray;">'.$memberName.'</h4><span style="color: grey;">'.$memberQ.'</span></div>
								</div>
							</div>
						</div>';

         }}

?>
					</div>
					
					<div class="clear"></div>

				</div>
			</div>
		
	</section>

<?php include("footer.php"); ?>
