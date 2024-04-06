<?php include("header.php"); ?>


		<div id="page-menu">

			<div id="page-menu-wrap">

			</div>

		</div>


		<section id="content">

			<div class="content-wrap" id="start">

			<div class="container clearfix">

					<div class="nobottommargin clearfix">

					<?php if(isset($_POST['categorie_op'])){

					 	$newOp = $_POST['categorie_op'];
					 	
					 }else{
                        $newOp = "";
                    }

					 
					?>   

					<form method="post">

					    <div class="form-group">                    
                        <label class="custom_label"> Categorie Selection</label>
                        <select class="form-control custom_input"  name="categorie_op" id="categorie_op" onchange='if(this.value != 0) { this.form.submit(); }'>
                        <option value="a">All</option>
                    <?php 
                             $query = "SELECT * FROM `categories`";

                        $result = mysqli_query($connection, $query);

                        if(mysqli_num_rows($result) > 0){
        
                        while( $row = mysqli_fetch_assoc($result) ){
                    ?>

                        <option <?php if($row['id'] == $newOp) { ?> selected <?php } ?> value="<?php echo $row['id']; ?>" > <?php echo $row['categorie']; ?>  </option>

                        <?php       
                            } }
                        ?>

                        </select>
                   
                </div>	

			</form>

            <?php

if(!empty($newOp) && $newOp != 'a'){ ?>

<table class="table table-striped table-bordered">
<tr>
    <th>Cover</th>
    <th>Name</th>
    <th>Description</th>
    <th>Download</th>
</tr>
<?php

    $query = "SELECT * FROM `library` WHERE categorieId='$newOp'";

    $result = mysqli_query($connection, $query);

    if(mysqli_num_rows($result) > 0){
    
     while( $row = mysqli_fetch_assoc($result) ){
            echo "<tr>";
            

            echo "<td width='100px' height='100px'><img src='admin/images/library/{$row["image"]}' width='100px' height='100px'>
            </td>";
            
            echo "<td><strong>".$row["name"]."</strong></td>";

            echo "<td>".$row["description"]."</td>";

            echo "<td width='50px'><a target='_blank' href='admin/books/{$row['book']}' type= 'button' class='btn  btn-sm' style='background-color: darkslategray;'>
            <span class='icon-download-alt'></span></a></td>";             


            echo "</tr>";  
        }
} else {
    echo "<div class='alert alert-danger'>Books Are Not Available Yet...!<a class='close' data-dismiss='alert'>&times</a></div>";
}

    mysqli_close($connection);
?>

<tr>
    <td colspan="5" id="end"><div class="text-center"><a href="library.php#start" type="button" class="btn btn-sm bg_slate"><span class="icon-arrow-up"></span></a></div></td>
</tr>
</table>
    
<?php	}else{

?>


<table class="table table-striped table-bordered">
<tr>
    <th>Cover</th>
    <th>Name</th>
    <th>Description</th>
    <th>Download</th>
</tr>
<?php

    $query = "SELECT * FROM `library`";

    $result = mysqli_query($connection, $query);

    if(mysqli_num_rows($result) > 0){
    

     while( $row = mysqli_fetch_assoc($result) ){
            echo "<tr>";


            echo "<td width='100px' height='100px'><img src=admin/images/library/".$row["image"]." style='object-fit: cover;' width='100px' height='100px' style='object-fit: cover;'>
            </td>";
            
            echo "<td><strong>".$row["name"]."</strong></td>";

            echo "<td>".$row["description"]."</td>";

            echo '<td width="50px"><a target="_blank" href="admin/books/'.$row['book']. '" type= "button" class="btn bg_slate btn-sm">
            <span class="icon-download-alt"></span></a></td>';             


            echo "</tr>";  
        }
} else {
    echo "<div class='alert alert-danger'>Books Are Not Available Yet...!<a class='close' data-dismiss='alert'>&times</a></div>";
}


    mysqli_close($connection);
?>

<tr>
    <td colspan="5" id="end"><div class="text-center"><a href="library.php#start" type="button" class="btn btn-sm bg_slate"><span class="icon-arrow-up"></span></a></div></td>
</tr>
</table>

<?php } ?>					
                
</div>	
</div>
</div>
</section>


<?php include("footer.php"); ?>
