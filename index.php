<?php
$con = mysqli_connect("localhost","root","","mfu");

if($con === false){
	die("ERROR: Could not connect." . mysqli_connect_error());
}

session_start();

if(!isset($_SESSION['c']) || !isset($_SESSION['login']) || !isset($_SESSION['lgid'])){
	$_SESSION['lgid'] = 0;
	$_SESSION['login'] = "GUEST";
	$_SESSION['c'] = 0;
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
		$_SESSION["lgid"] = $result['id'];
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
	$_SESSION["lgid"] = 0;
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
<head>
<style>

/* Full-width input fields */
input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

/* Set a style for all buttons */
button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
}

button:hover {
    opacity: 0.8;
}

/* Extra styles for the cancel button */
.cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
}

/* Center the image and position the close button */
.imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
    position: relative;
}

img.avatar {
    width: 40%;
    border-radius: 50%;
}

.container {
    padding: 16px;
}

span.psw {
    float: right;
    padding-top: 16px;
}

/* The Modal (background) */
.mymodal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    padding-top: 60px;
}

/* Modal Content/Box */
.mymodal-content {
    background-color: #fefefe;
    margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button (x) */
.myclose {
    position: absolute;
    right: 25px;
    top: 0;
    color: #000;
    font-size: 35px;
    font-weight: bold;
}

.myclose:hover,
.myclose:focus {
    color: red;
    cursor: pointer;
}

/* Add Zoom Animation */
.myanimate {
    -webkit-animation: animatezoom 0.6s;
    animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
    from {-webkit-transform: scale(0)} 
    to {-webkit-transform: scale(1)}
}
    
@keyframes animatezoom {
    from {transform: scale(0)} 
    to {transform: scale(1)}
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
    span.psw {
       display: block;
       float: none;
    }
    .cancelbtn {
       width: 100%;
    }
}
</style>
</head>
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
</table>
<br/><br/>

<button style="width:auto; border-radius:8px" onclick="location.href='/mfu/RegisterForm.php'" />Register</button><br/><br/>

<form method="post">
	<input type="submit" name="guest" value="Guest" />
</form>

<br/>
	<button style="width:80%; max-width:300px; border-radius:15px" onclick='window.location="ShowProduct.php"'>GO TO PRODUCTS PAGE</button>
<br/>

SESSIONS : <?php print_r($_SESSION); ?>
<br/><br/>

<form method="post">
	<input type="submit" name="clear" value="Clear Sessions" />
</form>


<button onclick="document.getElementById('loginbox').style.display='block'" style="width:auto; border-radius:15px">TESTLogin</button>

<div id="loginbox" class="mymodal">
  
  <form class="mymodal-content myanimate" action="/action_page.php">
    <div class="imgcontainer">
      <span onclick="document.getElementById('loginbox').style.display='none'" class="myclose" title="Close Modal">&times;</span>
      <img src="img_avatar2.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>
        
      <button type="submit">Login</button>
      <label>
        <input type="checkbox" checked="checked" name="remember"> Remember me
      </label>
    </div>

    <div class="container" style="background-color:#f1f1f1;">
      <button type="button" onclick="document.getElementById('loginbox').style.display='none'" class="cancelbtn">Cancel</button>
      <span class="psw">Forgot <a href="#">password?</a></span>
    </div>
  </form>
</div>




<script>
// Get the modal
var mymodal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == mymodal) {
        mymodal.style.display = "none";
    }
}
</script>
</body>
</html>