<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	session_start();
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	$thisid = $_GET['id'];
	
	mysqli_query($con,"SET NAMES UTF8");
	
	if(isset($_SESSION['c'])){
		if($_SESSION['c']>6){
			$sql = "DELETE FROM product WHERE id=$thisid";
			$qry = mysqli_query($con,$sql);
			if($qry){
				echo '<script language="javascript">';
				echo 'alert("Delete Sucess!");';
				mysqli_close($con);
				echo 'window.history.back();';
				echo '</script>';
				
			}
		}else{
			echo '<script>alert("PLEASE DO NOT TRY TO HACK!");</script>';
			echo '<script>window.history.back();</script>';
		}
	}else{
		echo '<script>alert("PLEASE DO NOT TRY TO HACK!");</script>';
		echo '<script>window.history.back();</script>';
	}

?>