<?php

$host = 'localhost';
$user = 'root';
$pass = '';

mysqli_connect($host,$user,$pass,"mfu") or die(mysql_error());

$id = addslashes($_REQUST['id']);

$image = mysqli_query("SELECT * FROM imagetb WHERE id=$id");
$image = mysqli_fetch_assoc($image);
$image = $image['image'];

header("Content-type: image/jpeg");

echo $image;
?>