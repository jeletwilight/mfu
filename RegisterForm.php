<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	session_start();
	session_destroy();
	
	mysqli_query($con,"SET NAMES UTF8");
	
	if(isset($_POST['submit'])){
		$usn = $_POST['username'];
		$pw = $_POST['password'];
		$type = 1;
		$name = $_POST['fullname'];
		$gender = $_POST['gender'];
		$birthdate = $_POST['bd'];
		$telephone = $_POST['tel'];
		$email = $_POST['mail'];
		$other = $_POST['other'];
		
		$sql1 = "SELECT * FROM login WHERE username='$usn'";
		$sql2 = "SELECT * FROM login WHERE email='$email'";
		$qry1 = mysqli_query($con, $sql1);
		$qry2 = mysqli_query($con, $sql2);
		if(mysqli_fetch_array($qry1) != false || mysqli_fetch_array($qry2) != false){
			echo '<script language="javascript">';
			echo 'alert("Existed Username or Email!")';
			echo '</script>';
		}else{
			$sql = "INSERT INTO login VALUES ('',
												'$usn',
												'$pw',
												'$type',
												'$name',
												'$gender',
												'$birthdate',
												'$telephone',
												'$email',
												'$other')";
												
			$qry = mysqli_query($con, $sql);
			if($qry){
				echo '<script language="javascript">';
				echo 'alert("Register Sucess!");';
				echo 'window.location.replace("/mfu/index.php");';
				echo '</script>';
			}
		}
	}
	
	
?>

<!DOCTYPE html>
<html>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>Register</title>
	<body>
		<center>
		<h1> Register </h1>
		<img id="output" height="200" width="200" />
		<br/><br/>
		<table>
			<form id="form1" method="post" action="" enctype="multipart/form-data" runat="server">
			<input type="hidden" name="type" value="1" />
			<tr>
				<td colspan="2"><a style="color:red;">* = Required</a></td>
			</tr>
			<tr>
				<td align="right">* Username :</td>
				<td>
					<textarea rows="1" cols="60" type="text" name="username" placeholder="Example: loginuser" minlength="6" maxlength="20" required></textarea>
				</td>
			</tr>
			<tr>
				<td align="right">* Password :</td>
				<td><textarea rows="1" cols="60" type="text" name="password" placeholder="Example: 123456" minlength="6" maxlength="20" required></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">* Fullname :</td>
				<td><textarea rows="1" cols="60" type="text" name="fullname" placeholder="Example: Tester Numberone" required></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">* Gender :</td>
				<td><input type="radio" name="gender" value="M" checked> Male
					<input type="radio" name="gender" value="F"> Female
				</td>
			</tr>
			<tr>
				<td align="right" valign="top">* Birth Date :</td>
				<td><input type="date" name="bd" required></input></td>
			</tr>
			<tr>
				<td align="right" valign="top">* Tel. :</td>
				<td><textarea rows="1" cols="60" type="text" name="tel" placeholder="Example: 0812345678" required></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">* E-mail :</td>
				<td><textarea rows="1" cols="60" type="text" name="mail" placeholder="Example: test@gmail.com" required></textarea></td>
			</tr>
			<!--<tr>
				<td align="right" valign="top">Address :</td>
				<td><textarea rows="1" cols="60" type="text" name="addr" placeholder="Example: 123/1 Moo.1" required></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Province :</td>
				<td><textarea rows="1" cols="60" type="text" name="prov" placeholder="Example: Chiang Rai" required></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">District :</td>
				<td><textarea rows="1" cols="60" type="text" name="dist" placeholder="Example: Mae Sai" required></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Sub-District :</td>
				<td><textarea rows="1" cols="60" type="text" name="sdist" placeholder="Example: Huai Khrai" required></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top">Zip Code :</td>
				<td><textarea rows="1" cols="60" type="text" name="zip" placeholder="Example: 57000" required></textarea></td>
			</tr>-->
			<tr>
				<td align="right" valign="top">Other :</td>
				<td><textarea rows="1" cols="60" type="text" name="other" placeholder="Example: LineID=userline,Facebook=John Doe"></textarea></td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<table>
			<tr>
				<td width="100"><input type="submit" name="submit" /></td>
				<td width="100"><input type="reset"></td>
				<td width="100"><button type="button" onclick="location.href='/mfu/index.php';">Back</button></td>
				<!------------------------------------  WAITING FOR LINK ^^^^ ----------------------->
			</tr>
			</form>
		</table>

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