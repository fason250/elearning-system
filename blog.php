<?php include("header.php");

	if(isset($_GET['id'])){

		$sendId=$_GET['id'];
	}

 ?>

		<div id="page-menu">

			<div id="page-menu-wrap">

			</div>

		</div>

<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<div class="postcontent nobottommargin clearfix">

						<div id="posts">

						<?php 

if(isset($sendId)){
	$query = "SELECT * FROM `blog` WHERE id='$sendId' ";
}else{
	$query = "SELECT * FROM `blog` ORDER BY id DESC limit 1";
}

$result = mysqli_query($connection, $query);

if(mysqli_num_rows($result) > 0){

while( $row = mysqli_fetch_assoc($result) ){ 
	 $postId = $row['id'];
	 $status =  $row["status"]; 
	$post = $row["post"];
	$title = $row["title"]; 
	 $content = $row["postContent"];
	 $adminPost = $row["admin"];
	 $postDate = $row["postDate"];

$query2 = "SELECT * FROM `admin` WhERE id='$adminPost'";

$result2 = mysqli_query($connection, $query2);

if(mysqli_num_rows($result2) > 0){         			

while( $row2 = mysqli_fetch_assoc($result2) ){
$adminName = $row2['name'];
}} else { 
	$adminName = 'Jey Fason';
}	


if($status=='image'){			
	echo'<div class="entry clearfix">
		<div class="entry-image">
			<img style=" height: 500px;" class="image_fade" src="admin/images/blog/'.$post.'" alt="jeyfason">
		</div>';					
}else{

echo'<div class="entry clearfix">
		<div class="entry-image" style=" height:450px;">
		
		<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$post.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>';
	
}
echo'<div class="entry-title">
			<h2>'.$title.'</h2>
		</div>
		<ul class="entry-meta clearfix">
			<li><i class="icon-calendar3"></i>'.$postDate.'</li>
			<li><i class="icon-user"></i>'.$adminName.'</li>

		</ul>
		<div class="entry-content">
			<p>'.$content.'</p>
			
		</div>
	</div>';	
}

}else{
echo '<div class="section notopmargin notopborder">
<div class="container clearfix">
<div class="heading-block center nomargin">
	<h3>Post not available</h3>
	</div>
</div>
</div>';
}


?>
							


							

						</div>

						
					</div>

				<div id="posts" class="post-grid grid-container clearfix nobottommargin nobottommargin">


				<?php

				$query = "SELECT * FROM `blog` ORDER BY id DESC limit 8";

				$result = mysqli_query($connection, $query);

        		if(mysqli_num_rows($result) > 0){
        
         			while( $row = mysqli_fetch_assoc($result) ){ 
         					$postId = $row['id'];
         					$status =  $row["status"]; 
            			    $post = $row["post"];
                			$title = $row["title"]; 
             			    $content = $row["postContent"];
             			    $adminPost = $row["admin"];
             			    $postDate = $row["postDate"];

				$query2 = "SELECT * FROM `admin` WhERE id='$adminPost'";

				$result2 = mysqli_query($connection, $query2);

        		if(mysqli_num_rows($result2) > 0){         			

        		while( $row2 = mysqli_fetch_assoc($result2) ){
        				$adminName = $row2['name'];
        		}} else { $adminName = 'Jey Fason';}	


					if($status=='image'){			
						echo'<div class="clearfix">
							<div class="entry-image">
								<a href="blog.php?id='.$postId.'"><img class="image_fade" src="admin/images/blog/'.$post.'" alt="Post"></a>
							</div>';						
					}else{

						echo'<div class="clearfix">
							<div class="entry-image">
								<a href="blog.php?id='.$postId.'" data-lightbox="image">
								 <iframe width="150px" height="150px" src="https://www.youtube.com/embed/'.$post.'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></a>
							</div>';
						
					}			
							echo'<div class="entry-title">
								<h2><a href="blog.php?id='.$postId.'">'.$title.'</a></h2>
							</div>
							<ul class="entry-meta clearfix">
								<li><i class="icon-calendar3"></i>'.$postDate.'</li>
								<li><i class="icon-user"></i>'.$adminName.'</li>
							</ul>
							<div class="entry-content">
								<a href="blog.php?id='.$postId.'" class="more-link">Read More</a>
							</div>
						</div>';		
         			}

         		}else{
         			echo '<div class="section notopmargin notopborder">
					<div class="container clearfix">
						<div class="heading-block center nomargin">
							<h3>Posts are not available Yet</h3>
							</div>
						</div>
					</div>';
         		}

			
			?>	
		
						</div>
					</div>
	
				</div> 

		</section>

<?php include("footer.php"); ?>