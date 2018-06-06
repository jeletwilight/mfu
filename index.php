<?php
$con = mysqli_connect("localhost","root","","mfu");

session_start();
if(!isset($_SESSION['c'])){
	$_SESSION['login'] = "GUEST";
	$_SESSION['c'] = 0;
}

if($con === false){
	die("ERROR: Could not connect." . mysqli_connect_error());
}

if(isset($_POST['submit'])){
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	
	$sql = "SELECT * FROM login WHERE username='$user' AND password='$pass' LIMIT 1";
	$qry = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($qry);
	
	if($result != false){
		echo '<script language="javascript">';
		echo 'alert("Founded")';
		echo '</script>';
		$_SESSION["login"] = $result['username'];
		$_SESSION["c"] = $result['type'];
		echo '<script>window.location="ShowProduct.php";</script>';
	}else{
		echo '<script language="javascript">';
		echo 'alert("Not Founded!")';
		echo '</script>';
	}
}

if(isset($_POST['guest'])){
	echo '<script language="javascript">';
	echo 'alert("As Guest")';
	echo '</script>';
	$_SESSION["login"] = "GUEST";
	$_SESSION["c"] = 0;
	echo '<script>window.location="ShowProduct.php";</script>';
}

if(isset($_POST['clear'])){
	session_destroy();
	echo '<script>window.location="index.php";</script>';
}

?>

<!DOCTYPE html>
<html>
<title>
Home
</title>
<body>
<center>
<h1> Welcome </h1>
<table>
<form method="post" action="" enctype="multipart/form-data">
	<tr>
		<td>Username </td>
		<td><input type="text" name="user" required /></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" name="pass" required /></td>
	</tr>
	</table><br/>
	
	<input type="submit" name="submit" value="login" />
	<input type="reset">
</form>
</table><br/><br/>
<button onclick="location.href='/mfu/RegisterForm.php'" />Register</button><br/><br/>
<form method="post">
	<input type="submit" name="guest" value="Guest" />
</form>
<?php print_r($_SESSION); ?>
<br/><br/>
<form method="post">
	<input type="submit" name="clear" value="Clear" />
</form>
</body>
</html>