<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	$thisid = $_GET['id'];
	
	mysqli_query($con,"SET NAMES UTF8");
	
	/*$sql = "SELECT * FROM product WHERE id=$thisid";
	$qry = mysqli_query($con,$sql);
	
	$row = mysqli_fetch_array($qry);
	$pic = $row['imgname'];
	
	if (!unlink("/../productimages/$pic")){
		echo ("Error deleting file");
	}else{
		echo ("Deleted file");
	}*/
	
	$sql = "DELETE from product WHERE id=$thisid";
	$qry = mysqli_query($con,$sql);
	
	if($qry){
		echo '<script language="javascript">';
		echo 'alert("Delete Sucess!");';
		mysqli_close($con);
		echo 'window.location.replace("/mfu/ShowProduct.php");';
		echo '</script>';
		
	}

?>