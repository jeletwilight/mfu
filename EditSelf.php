<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	session_start();
	
	mysqli_query($con,"SET NAMES UTF8");
	
	$lgid = $_SESSION['lgid'];
	
	$sql = "SELECT * FROM login WHERE id='$lgid'";
	$qry = mysqli_query($con,$sql);
	$resultlogin = mysqli_fetch_array($qry);
	
	if($resultlogin != 0){
		$usrname = $resultlogin['username'];
		$oldname = $resultlogin['name'];
		$oldtelephone = $resultlogin['telephone'];
		$oldother = $resultlogin['other'];
	}else{
		echo "<script>alert('Please Login!')</script>";
		echo "<script>window.location='/mfu/index.php'</script>";
	}
	
	$sql = "SELECT * FROM address WHERE user_id='$lgid' AND current=1";
	$qry = mysqli_query($con, $sql);
	$resultaddress = mysqli_fetch_array($qry);
	
	if($resultaddress != 0){
		$oldlocation = $resultaddress['location'];
		$oldsubdist = $resultaddress['subdistrict'];
		$olddist = $resultaddress['district'];
		$oldprovince = $resultaddress['province'];
		$oldzip = $resultaddress['zipcode'];
		$oldteladdr = $resultaddress['telephone'];
	}else{
		$oldlocation = "";
		$oldsubdist = "";
		$olddist = "";
		$oldprovince = "";
		$oldzip = "";
		$oldteladdr = "";
	}
	
	$sql = "SELECT * FROM creditcard WHERE user_id='$lgid'";
	$qry = mysqli_query($con,$sql);
	$resultpay = mysqli_fetch_array($qry);
	
	if(isset($_POST['submitprofile'])){
		$name = $_POST['fullname'];
		$telephone = $_POST['tel'];
		$other = $_POST['other'];
		
		if($name == "" || $telephone == ""){
			echo "<script>alert('Please Fill Name and Telephone Fields')</script>";
		}else{
			$qry = "UPDATE login SET name='$name', telephone='$telephone', other='$other' WHERE id='$lgid'";
			if(mysqli_query($con,$qry)){
				echo "<script>alert('Update Success')</script>";
				echo "<script>window.location='/mfu/ShowProduct.php'</script>";
			}
		}
		
	}
	
	if(isset($_POST['submitaddr'])){
		if($_POST['addr'] == "" || $_POST['prov'] == "" || $_POST['dist'] == "" || $_POST['sdist'] == "" || $_POST['zip'] == "" || $_POST['teladdr'] == ""){
			echo "<script>alert('Please Fill All In ADDRESS Form')</script>";
		}
		else{
			$qry = "UPDATE address SET current='0' WHERE user_id='$lgid' AND current='1'";
			mysqli_query($con, $qry);
			$location = $_POST['addr'];
			$province = $_POST['prov'];
			$district = $_POST['dist'];
			$subdist = $_POST['sdist'];
			$zipcode = $_POST['zip'];
			$teladdr = $_POST['teladdr'];
			$qry2 = "INSERT INTO address VALUES ('','$lgid','$location','$subdist','$district','$province','$zipcode','$teladdr','1')";
			if(mysqli_query($con, $qry2)){
				echo "<script>alert('Changed Default Address')</script>";
				echo "<script>window.location='/mfu/ShowProduct.php'</script>";
			}
		}
	}
	
	if(isset($_POST['submitpay'])){
		
		if($_POST['holder'] == "" || $_POST['cdnum1'] == "" || $_POST['cdnum2'] == "" || $_POST['cdnum3'] == "" || $_POST['cdnum4'] == ""){
			echo "<script>alert('Please Fill All in PATMENT Form')</script>";
		}
		else{
			$holder = $_POST['holder'];
			$card = $_POST['cdnum1'].$_POST['cdnum2'].$_POST['cdnum3'].$_POST['cdnum4'];
			$qry = "SELECT * FROM creditcard WHERE cardnumber='$card'";
			$dupcardqry = mysqli_query($con, $qry);
			if(mysqli_fetch_array($dupcardqry) != 0){
				$qry2 = "UPDATE creditcard SET holder_name='$holder', current='1' WHERE cardnumber='$card' AND current='0'";
			}else{
				$clearqry = "UPDATE creditcard SET current='0' WHERE user_id='$lgid' AND current='1'";
				mysqli_query($con,$clearqry);
				$qry2 = "INSERT INTO creditcard VALUES ('','$lgid','$holder','$card','','','','','1')";
			}
			if(mysqli_query($con,$qry2)){
				echo "<script>alert('Changed Default Payment')</script>";
				echo "<script>window.location='/mfu/ShowProduct.php'</script>";
			}
		}
	}
	
?>

<!DOCTYPE html>
<html>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>Edit Profile</title>
	<body>
		<center>
		<h1> Edit Profile </h1>
		<h5>Account : <?php echo $usrname;?></h5>
		<br/>
		<table>
			<form id="form2" method="post" action="">
			<tr>
				<td align="right" valign="top">Fullname :</td>
				<td><textarea rows="1" cols="60" type="text" name="fullname" placeholder="Example: Tester Numberone" required><?php echo $oldname;?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Tel. :</td>
				<td><textarea rows="1" cols="60" type="text" name="tel" placeholder="Example: 0812345678" required><?php echo $oldtelephone;?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Other :</td>
				<td><textarea rows="1" cols="60" type="text" name="other" placeholder="Example: LineID=userline,Facebook=John Doe"><?php echo $oldother;?></textarea></td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<table>
			<tr>
				<td width="100" align="center"><input type="submit" name="submitprofile" /></td>
				<!------------------------------------  WAITING FOR LINK ^^^^ ----------------------->
			</tr>
			</form>
		</table>
		<br/><br/>
		<h1> Edit Address </h1>
		<br/>
		<table>
			<form id="form1" method="post" action="">
			<tr>
				<td align="right" valign="top">Address :</td>
				<td><textarea rows="1" cols="60" type="text" name="addr" placeholder="Example: 123/1 Moo.1" required><?php echo $oldlocation;?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Province :</td>
				<td><textarea rows="1" cols="60" type="text" name="prov" placeholder="Example: Chiang Rai" required><?php echo $oldprovince;?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">District :</td>
				<td><textarea rows="1" cols="60" type="text" name="dist" placeholder="Example: Mae Sai" required><?php echo $olddist;?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Sub-District :</td>
				<td><textarea rows="1" cols="60" type="text" name="sdist" placeholder="Example: Huai Khrai" required><?php echo $oldsubdist;?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Zip Code :</td>
				<td><textarea rows="1" cols="60" type="text" name="zip" placeholder="Example: 57000" required><?php echo $oldzip;?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Tel. (For Shipping) :</td>
				<td><textarea rows="1" cols="60" type="text" name="teladdr" placeholder="Example: 0801234567" required><?php echo $oldteladdr;?></textarea></td>
			</tr><td height="10"/></tr>	
		</table>
		<table>
			<tr>
				<td width="100" align="center"><input type="submit" name="submitaddr" /></td>
				<!------------------------------------  WAITING FOR LINK ^^^^ ----------------------->
			</tr>
			</form>
		</table>
		<br/><br/>
		<h1> Edit Payment Method </h1>
		<br/>
		<table>
			<form id="form3" method="post" action="">
			<tr>
				<td align="right" valign="top">Holder name: </td>
				<td><textarea rows="1" cols="40" type="text" name="holder" placeholder="Full Name" required><?php echo $resultpay['holder_name'];?></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Card Number: </td>
				<td>
					<textarea rows="1" cols="5" type="text" name="cdnum1" placeholder="xxxx" required><?php echo substr($resultpay['cardnumber'],0,4);?></textarea>
					<textarea rows="1" cols="5" type="text" name="cdnum2" placeholder="xxxx" required><?php echo substr($resultpay['cardnumber'],4,4);?></textarea>
					<textarea rows="1" cols="5" type="text" name="cdnum3" placeholder="xxxx" required><?php echo substr($resultpay['cardnumber'],8,4);?></textarea>
					<textarea rows="1" cols="5" type="text" name="cdnum4" placeholder="xxxx" required><?php echo substr($resultpay['cardnumber'],12,4);?></textarea>
				</td>
			</tr>
		</table><br/>
		<table>
			<tr>
				<td width="100" align="center"><input type="submit" name="submitpay" /></td>
				<!------------------------------------  WAITING FOR LINK ^^^^ ----------------------->
			</tr>
			</form>
		</table>
		<br/><br/>
		<center><button type="button" onclick="location.href='/mfu/ShowProduct.php';">Back</button></center>
		<br/><br/>

		<!--==========================================================================================-->
		<script>
			var loadFile = function(event) {
				var output = document.getElementById('output');
				output.src = URL.createObjectURL(event.target.files[0]);
			};
		</script>
		<!--==========================================================================================-->
	</body>
</html>

<?php mysqli_close($con); ?>