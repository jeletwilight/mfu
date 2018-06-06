<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	session_start();
	
	if(!isset($_SESSION['c'])){
		$_SESSION['c']=0;
	}
	$allowupdate = array(8,9);
	
	
	
	$thisid = $_GET['id'];
	
	$old = mysqli_query($con,"SELECT * FROM product WHERE id='$thisid'");
	$thisrow = mysqli_fetch_array($old);
	if($thisrow != false){
		$oldproductname = $thisrow['name'];
	}
	
	if(isset($_POST['submit'])){
	
		$price = $_POST['price2'];
		$addstock = number_format($_POST['addstock']);
		$sale = $_POST['sale2'];
		$info = $_POST['info2'];
		
		if($sale==0 or $sale=='NULL'){
			$real=$price;
		}else{
			$real=$sale;
		}
		
		mysqli_query($con,"SET NAMES UTF8");
		$sql = "UPDATE product SET instock=instock+'$addstock', price=$price, sale=$sale, currentprice=$real, information='$info' WHERE id=$thisid";
		$qry = mysqli_query($con, $sql);
		
		if($qry){
			echo '<script language="javascript">';
			echo 'alert("Update Sucess!");';
			echo 'window.location.replace("/mfu/UpdateForm.php?id='.$thisid.'");';
			echo '</script>';
		}
	}
	
	if(isset($_POST['add_to_cart'])){
		
		$per = false;
		if($_POST['hidden_instock']){
			if(number_format($_POST['hidden_instock']) > 0 && number_format($_POST['quan']) <= number_format($_POST['hidden_instock']))$per = true;
		}else{
			$per = false;
		}
		if($per == true){
			if(isset($_SESSION["shopping_cart"])){
				$item_array_id = array_column($_SESSION["shopping_cart"],"item_id");
				if(!in_array($_GET["id"],$item_array_id)){
					$count = count($_SESSION["shopping_cart"]);
					$item_array = array(
					'item_id' => $_GET["id"],
					'item_name' => $_POST["hidden_name"],
					'item_price' => $_POST["hidden_price"],
					'item_quantity' => $_POST["quan"]);
					$_SESSION["shopping_cart"][$count] = $item_array;
					echo '<script>alert("Add Success!");</script>';
					echo '<script>window.location="ShowProduct.php";</script>';
					
				}else{
					echo '<script>alert("Item Already Added");</script>';
					echo '<script>window.location="ShowProduct.php";</script>';
				}
			}else{
				$item_array = array(
					'item_id' => $_GET["id"],
					'item_name' => $_POST["hidden_name"],
					'item_price' => $_POST["hidden_price"],
					'item_quantity' => $_POST["quan"]);
				$_SESSION["shopping_cart"][0] = $item_array;
				echo '<script>alert("Add Success!");</script>';
				echo '<script>window.location="ShowProduct.php";</script>';
			}
		}else{
			echo '<script>alert("Run Out of this product!")</script>';
			//echo '<script>window.location="ShowProduct.php";</script>';
		}
	}
?>

<!DOCTYPE html>
<html>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<style>
	container {
		font-family: Arial;
		color: white;
	}

	.split {
		height: 100%;
		width: 50%;
		position: fixed;
		z-index: 1;
		top: 0;
		overflow-x: hidden;
		padding-top: 0;
	}

	.left {
		left: 0;
		background-color: #ffffff;
	}

	.right {
		right: 0;
		background-color: #ffffff;
	}

	.centered {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		text-align: center;
	}

	.centered img {
		width: 150px;
		border-radius: 50%;
	}
</style>
	<title><?php echo $oldproductname;?></title>
	<body>
		<?php
			mysqli_query($con,"SET NAMES UTF8");
			$id = $_GET['id'];
			$qry = "SELECT * FROM product WHERE id=".$id;
			$result = mysqli_query($con,$qry);
			$row = mysqli_fetch_array($result);
			$id = $row['id'];
			$name = $row['name'];
			$price = $row['price'];
			$instock = $row['instock'];
			$real = $row['currentprice'];
			$sale = $row['sale'];
			$info = $row['information'];
			$image = $row['imgname'];
		?>	
		<center>
		<!---------------------------------MANAGER------------------------------------------>
<?php if(in_array($_SESSION['c'],$allowupdate)):?>
		<div class='container split left'>
			<h1><?php echo $oldproductname;?></h1>
			<br/>
			<table>
				<tr>
					<td colspan="2" align="center">
						<img src="/mfu/productimages/<?php echo $image; ?>" width="200" height="/width*200">
					</td>
				</tr>
				<form id="form1" method="post" action="">
				<tr>
					<th align="right">Product Name :&nbsp;</th>
					<td align="left"><?php echo $name;?></td>
				</tr>
				<tr>
					<th align="right">Product Price :&nbsp;</th>
					<td align="left"><?php 
								if($sale==0){echo "฿".$price;}
								else{echo "<S style='color:red;'><h>";
										echo "฿".$price;
										echo "</h></S> → ";
										echo "฿".$sale;
									}
							?>
					</td>
				</tr>
				<tr>
					<th align="right">Product In Stock :&nbsp;</th>
					<td align="left"><?php echo $instock;?></td>
				</tr>
				<tr>
					<th align="right" valign="top">Information :&nbsp;</th>
					<td align="left" valign="top" width="400" height="300"><?php echo $info;?></td>
				</tr>
				<tr><td height="10"/></tr>	
			</table>
		</div>
		<div class='container split right'>
			<h1> EDIT PRODUCT </h1>
			<br/><br/>
			<table>
				<tr>
					<td colspan="2" align="center">
						<img src="/mfu/productimages/<?php echo $image; ?>" width="200" height="/width*200">
					</td>
				</tr>
				<form id="form1" method="post" action="">
				<tr>
					<th align="right">Product Name :&nbsp;</th>
					<td><?php echo $name;?></td>
				</tr>
				<tr>
					<th align="right">Product Price :&nbsp;</th>
					<td><textarea rows="1" cols="60" type="text" name="price2"><?php echo $price;?></textarea></td>
				</tr>
				<tr>
					<th align="right">Sale :&nbsp;</th>
					<td><textarea rows="1" cols="60" type="text" name="sale2"><?php if($sale)echo $sale;else echo 0;?></textarea></td>
				</tr>
				<tr>
					<th align="right">Add to Stock :&nbsp;</th>
					<td><textarea rows="1" cols="60" type="number" name="addstock" value="0">0</textarea></td>
				</tr>
				<tr>
					<th align="right" valign="top">Information :&nbsp;</th>
					<td><textarea rows="13" cols="60" type="text" name="info2"><?php echo $info;?></textarea></td>
				</tr>
				<tr><td height="10"/></tr>	
			</table>
			<table>
				<tr>
					<td width="100"><input type="submit" name="submit" /></td>
					<td width="100"><input type="reset"></td>
					<td width="100"><button type="button" onclick="location.href='/mfu/ShowProduct.php';">Back</button></td>
				</tr>
				</form>
			</table>
		</div>
		<!-------------------------------------------------------------------------------->
		<!---------------------------------CUSTOMER----------------------------------------->
<?php elseif($_SESSION['c']==1):?>
		<h1><?php echo $oldproductname;?></h1>
		<br/>
		<table>
			<tr>
				<td colspan="2" align="center">
					<img src="/mfu/productimages/<?php echo $image; ?>" width="200" height="/width*200">
				</td>
			</tr>

			<tr>
				<th align="right">Product Name :&nbsp;</th>
				<td align="left"><?php echo $name;?></td>
			</tr>
			<tr>
				<th align="right">Product Price :&nbsp;</th>
				<td align="left"><?php 
								if($sale==0){echo "฿".$price;}
								else{echo "<S style='color:red;'><h>";
										echo "฿".$price;
										echo "</h></S> → ";
										echo "฿".$sale;
									}
							?>
				</td>
			</tr>
			<tr>
				<th align="right" valign="top">Information :&nbsp;</th>
				<td align="left" valign="top" width="400" height="200"><?php echo $info;?></td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<table>
			<form method="post">
			<tr>
				<th>Quantity</th>
					<input type="hidden" name="hidden_name" value="<?php echo $name;?>" />
					<input type="hidden" name="hidden_price" value="<?php echo $real;?>" />
					<input type="hidden" name="hidden_instock" value="<?php echo $instock;?>" />
					<td><input type="number" name="quan" value=1 min="1" max="9" style="width:80px; text-align:center"></td>
					<td><button type="submit" name="add_to_cart">Add to Cart</button></td>
				<td width="100"><button type="button" onclick="location.href='/mfu/ShowProduct.php';">Back</button></td>
			</tr>
			</form>
		</table>
		<!---------------------------------------------------------------------------------->
		<!---------------------------------GUEST------------------------------------------>
<?php else:?>
		<h1><?php echo $oldproductname;?></h1>
		<br/>
		<table>
			<tr>
				<td colspan="2" align="center">
					<img src="/mfu/productimages/<?php echo $image; ?>" width="200" height="/width*200">
				</td>
			</tr>
			<tr>
				<th align="right">Product Name :&nbsp;</th>
				<td align="left"><?php echo $name;?></td>
			</tr>
			<tr>
				<th align="right">Product Price :&nbsp;</th>
					<td align="left"><?php 
								if($sale==0){echo "฿".$price;}
								else{echo "<S style='color:red;'><h>";
										echo "฿".$price;
										echo "</h></S> → ";
										echo "฿".$sale;
									}
							?>
					</td>
			</tr>
			<tr>
				<th align="right" valign="top">Information :&nbsp;</th>
				<td align="left" valign="top" width="400" height="300"><?php echo $info;?></td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<table>
			<tr>
				<td width="100"><button type="button" onclick="location.href='/mfu/ShowProduct.php';">Back</button></td>
			</tr>
		</table>
<?php endif;?>
		
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