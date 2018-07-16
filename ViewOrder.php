<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	session_start();
	
	mysqli_query($con,"SET NAMES UTF8");
	
	//---------------STARTER QUERY-----------------//
	$qry = "SELECT * FROM receipt";
    $result = mysqli_query($con,$qry);
	//---------------------------------------------//
	
	if(isset($_GET['deleteid'])){
		$delsql = "DELETE FROM receipt WHERE id=".$_GET['deleteid'];
		if(mysqli_query($con, $delsql)){
			echo "<script>alert('Delete Success')</script>";
		}
		echo "<script>window.location='/mfu/ViewOrder.php'</script>";
	}
	
	if(isset($_POST['Delivered'])){
		$updateqry = "UPDATE receipt SET status='1' WHERE id='".$_POST['staid']."'";
		if(mysqli_query($con,$updateqry)){
			echo "<script>alert('".$_POST['staid']." Delivered')</script>";
			echo "<script>window.location='/mfu/ViewOrder.php'</script>";
		}
	}
	
	if(isset($_POST['Nothing'])){
		$updateqry = "UPDATE receipt SET status='0' WHERE id='".$_POST['staid']."'";
		if(mysqli_query($con,$updateqry)){
			echo "<script>alert('".$_POST['staid']." Nothing')</script>";
			echo "<script>window.location='/mfu/ViewOrder.php'</script>";
		}
	}
	
	if(isset($_POST['sortnothing'])){
		$qry = "SELECT * FROM receipt WHERE status='0'";
		$result = mysqli_query($con,$qry);	
	}
	
	if(isset($_POST['sortall'])){
		$qry = "SELECT * FROM receipt";
		$result = mysqli_query($con,$qry);
	}
	
	if(isset($_GET['search-receipt'])){
		if($_GET['searchkey']){
			$searchid = $_GET['searchkey'];
			$qry = "SELECT * FROM receipt WHERE id='$searchid'";
			$result = mysqli_query($con,$qry);
		}else{
			$qry = "SELECT * FROM receipt";
			$result = mysqli_query($con,$qry);
		}
	}
	
	
?>

<html>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>ORDER List</title>
    <header>
	<?php print_r($_SESSION);?>
	<style>

button:hover:not(.active) {
	transition-duration: 0.4s;
	background-color: #AAA;
}

.normalbtn:hover:not(.active) {
	transition-duration: 0.4s;
	background-color: #AAA;
}
	
.statusdelivery:hover:not(.active) {
	color: #125512;
	transition-duration: 0.4s;
	background-color: #70ff70;
	border: 2px solid #4CAF50;
}

.statusnothing:hover:not(.active)	{
	color: #FFF;
	transition-duration: 0.4s;
	background-color: #ff8080;
	border: 2px solid #AF4C50;
}

.backbtn {
	padding: 0.8% 1.2%;
	border-radius: 30%;
	font-weight: bold;
}

	</style>
	</header>
    <body>
	<center>
	<h1>Order List</h1>
	<?php if($_SESSION['c']>=8):?>
		<table>
		<form method="post">
		<tr>
			<td><input class="normalbtn" type="submit" name="sortall" value="View All" /></td>
			<td><input class="normalbtn" type="submit" name="sortnothing" value="Just Nothing" /></td>
		</tr>
		</form>
		</table><br/>
		<form method="get">
			<input  type="text" name="searchkey" placeholder="Search Receipt ID..." />
			<button  type="submit" name="search-receipt">
				<i>Search</i>
			</button>
		</form>
	<?php endif;?>
	<table border=1 width="80%" style="max-width:1000px;">
		<tr>
			<th align="center" width="10%">RECEIPT ID</th>
			<?php if($_SESSION['c']>=8):?>
				<th align="center">USER</th>
			<?php endif;?>
			<th align="center">ADDRESS</th>
			<th align="center" width="10%">PRICE</th>
			<th align="center" width="10%">DETAIL</th>
			<?php if($_SESSION['c']>=8):?>
				<th align="center">STATUS</th>
				<th align="center" width="10%">DELETE</th>
			<?php endif;?>
		</tr>
		<?php if($_SESSION['c']>=8):?>
			<?php $countqry = mysqli_query($con,"SELECT * FROM receipt"); echo "Total : ".mysqli_num_rows($countqry);?><br/><br/>
			<?php 
				while ($row = mysqli_fetch_array($result)):
					$iden = $row['id'];
					$uid = $row['user_id'];
					$aid = $row['address_id'];
					$pid = $row['payment_id'];
					$total = $row['subtotal'];
					$status = $row['status'];
			?>
				<?php $userinfo = mysqli_query($con,"SELECT * FROM login WHERE id='".$uid."'"); $resultusr = mysqli_fetch_array($userinfo);?>
				<tr>
					<td align="center" bgcolor="<?php if($status==0)echo "#ff8080";else echo "#70ff70";?>"><?php echo $iden;?></td>
					<td align="center"><?php echo $resultusr['username'];?></td>
					<?php $addressinfo = mysqli_query($con, "SELECT * FROM address WHERE id='$aid'"); $resultaddress = mysqli_fetch_array($addressinfo);?>
					<td align="center">
						<?php echo $resultaddress['location'].
									" // ต.".$resultaddress['subdistrict'].
									" // อ.".$resultaddress['district'].
									" // จ.".$resultaddress['province'].
									"<br/>Zip:".$resultaddress['zipcode'].
									" // Tel:".$resultaddress['telephone'];
						;?>
					</td>
					<td align="center"><?php echo number_format($total)." ฿";?></td>
					<td rowspan=2 align="center" valign="top">
						<button style="width:100%; height:55px" onclick="hiddenlist('<?php echo $iden;?>')">CLICK</button>
					</td>
					<td rowspan=2 valign="top">
						<table width="100%">
						<form method="post">
							<input type="hidden" name="staid" value="<?php echo $iden;?>">
							<tr>
								<td width="100%">
									<input class="statusdelivery" type="submit" name="Delivered" value="Delivered" style="width:100%" />
								</td>
							</tr>
							<tr>
								<td width="100%">
									<input class="statusnothing" type="submit" name="Nothing" value="Nothing" style="width:100%" />
								</td>
							</tr>
						</form>
						</table>
					</td>
					<td align="center" rowspan=2 valign="top">
						<button style="width:100%; height:55px" onclick="if(confirm('This will delete customer order?'))
															{location.href='/mfu/ViewOrder.php?deleteid=<?php echo $iden;?>'}">x</button>
					</td>
				</tr>
				<tr>
					<td colspan=4>
						<table border=1 id="hiddenitem<?php echo $iden;?>" style="display:none;">
						<tr>
							<td align="center">Product</td>
							<td align="center">Quantity</td>
							<td align="center">Total</td>
						</tr>
						<?php $subqry = "SELECT * FROM lineitems WHERE receipt_id='$iden' ORDER BY lineprice DESC"; $subresult = mysqli_query($con,$subqry);?>
						<?php while ($subrow = mysqli_fetch_array($subresult)):?>
						<?php $moreinfo = mysqli_query($con,"SELECT * FROM product WHERE id='".$subrow['product_id']."'"); $morerow = mysqli_fetch_array($moreinfo);?>
							<tr>
								<td align="center"><?php echo $morerow['name'];?></td>
								<td align="center"><?php echo $subrow['quantity'];?></td>
								<td align="center"><?php echo number_format($subrow['lineprice'])." ฿";?></td>
							</tr>
						<?php endwhile;?>
						</table>
					</td>
					
				</tr>	
			<?php endwhile;?>
		<?php else:?>
			<?php 
				$mysql = "SELECT * FROM receipt WHERE user_id=".$_SESSION['lgid'];
				$myresult = mysqli_query($con, $mysql);
				while ($row = mysqli_fetch_array($myresult)):
					$iden = $row['id'];
					$uid = $row['user_id'];
					$aid = $row['address_id'];
					$pid = $row['payment_id'];
					$total = $row['subtotal'];
			?>
				<tr>
					<td align="center"><?php echo $iden;?></td>
					<?php $addressinfo = mysqli_query($con, "SELECT * FROM address WHERE id='$aid'"); $resultaddress = mysqli_fetch_array($addressinfo);?>
					<td align="center">
						<?php echo $resultaddress['location'].
									" // ต.".$resultaddress['subdistrict'].
									" // อ.".$resultaddress['district'].
									" // จ.".$resultaddress['province'].
									"<br/>Zip:".$resultaddress['zipcode'].
									" // Tel:".$resultaddress['telephone'];
						;?>
					</td>
					<td align="center"><?php echo number_format($total)." ฿";?></td>
					<td align="center" rowspan=2 valign="top"><button style="width:100%; height:55px" onclick="hiddenlist('<?php echo $iden;?>')">CLICK</button></td>
				</tr>
				<tr>
					<td colspan=3>
						<table border=1 id="hiddenitem<?php echo $iden;?>" style="display:none">
						<tr>
							<td align="center">Product</td>
							<td align="center">Quantity</td>
							<td align="center">Total</td>
						</tr>
						<?php $subqry = "SELECT * FROM lineitems WHERE receipt_id='$iden' ORDER BY lineprice DESC"; $subresult = mysqli_query($con,$subqry);?>
						<?php while ($subrow = mysqli_fetch_array($subresult)):?>
							<?php $moreinfo = mysqli_query($con,"SELECT * FROM product WHERE id='".$subrow['product_id']."'"); $morerow = mysqli_fetch_array($moreinfo);?>
							<tr>
								<td align="center"><?php echo $morerow['name'];?></td>
								<td align="center"><?php echo $subrow['quantity'];?></td>
								<td align="center"><?php echo number_format($subrow['lineprice'])." ฿";?></td>
							</tr>
						<?php endwhile;?>
						</table>
					</td>
				</tr>
			<?php endwhile;?>
		<?php endif;?>
	</table>
	<br/>
	<button class="backbtn" onclick="location.href='/mfu/ShowProduct.php'">Back</button>
	</center>
	
	
<script>
function hiddenlist(iden) {
	var y = "hiddenitem"+iden;
    var x = document.getElementById(y);
    if (x.style.display === "none") {
		x.align = "right";
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>
	</body>
</html>


<?php mysqli_close($con); ?>