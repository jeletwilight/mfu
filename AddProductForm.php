<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	session_start();
	$allowadd = array(8,9);
	
	if(!isset($_SESSION['c'])){
		$_SESSION['c']=0;
	}

	if(isset($_POST['submit'])){
	
		$fileupload = $_FILES['fileupload']['name'];
		$fileuploadTMP = $_FILES['fileupload']['tmp_name'];
		$folder = "productimages/";
		
		$pname = $_POST['pname'];
		$price = $_POST['price'];
		$info = $_POST['info'];
		$real = $price;
		$time = $_POST['time'];
		
		if($fileuploadTMP == null){
			echo '<script language="javascript">';
			echo 'alert("Please select an image!")';
			echo '</script>';
		}
		else{
			mysqli_query($con,"SET NAMES UTF8");
			$img = addslashes(($_FILES['fileupload']['tmp_name']));
			$img_name = addslashes($_FILES['fileupload']['name']);
			$result = mysqli_query($con, "SELECT * FROM `product` WHERE name='$pname' LIMIT 1");
			$result2 = mysqli_query($con, "SELECT * FROM `product` WHERE imgname='$img_name' LIMIT 1");
			
			if(mysqli_fetch_array($result) != false || mysqli_fetch_array($result2) != false){
				echo '<script language="javascript">';
				echo 'alert("Existed product name or image name!")';
				echo '</script>';
			}
			else{
				$sql = "INSERT INTO `product` VALUES ('','$pname','','$price','NULL','$real','$info','$img_name','$img','$time')";
				$qry = mysqli_query($con, $sql);
				move_uploaded_file($fileuploadTMP,$folder.$fileupload);
				if($qry){
					echo '<script language="javascript">';
					echo 'alert("Add Sucess!");';
					echo 'window.location.replace("/mfu/ShowProduct.php");';
					echo '</script>';
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>ADD Product</title>
	<body>
		<?php if(in_array($_SESSION['c'],$allowadd)):?>
		<center>
		<h1> ADD PRODUCT </h1>
		<img id="output" height="200" width="200" />
		<br/><br/>
		<table>
			<form id="form1" method="post" action="" enctype="multipart/form-data" runat="server">
			<tr>
				<td width="100" align="right">Picture</td>
				<td width="200"><input type="file" name="fileupload" id="fileupload" accept="image/*" onchange="loadFile(event)" /></td>
			</tr>
			<tr>
				<td align="right">Product Name</td>
				<td><textarea rows="1" cols="60" type="text" name="pname" required></textarea></td>
			</tr>
			<tr>
				<td align="right">Product Price</td>
				<td><textarea rows="1" cols="60" type="text" name="price"></textarea></td>
			</tr>
			<tr>
				<td align="right">Released Date</td>
				<td><input type="date" name="time" id="today" /></td>
			</tr>
			<tr>
				<td align="right" valign="top">Information</td>
				<td><textarea rows="15" cols="60" type="text" name="info"></textarea></td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<table>
			<tr>
				<td width="100"><input type="submit" name="submit" /></td>
				<td width="100"><input type="reset"></td>
				<td width="100"><button type="button" onclick="location.href='/mfu/ShowProduct.php';">Back</button></td>
				<!------------------------------------  WAITING FOR LINK ^^^^ ----------------------->
			</tr>
			</form>
		</table>
		<?php endif;?>
		<!--==========================================================================================-->
		<script>
			var loadFile = function(event) {
				var output = document.getElementById('output');
				output.src = URL.createObjectURL(event.target.files[0]);
			};
			
		document.querySelector("#today").valueAsDate = new Date();
		
		</script>
		<!--==========================================================================================-->
	</body>
</html>

<?php mysqli_close($con); ?>