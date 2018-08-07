<?php
	$host = "localhost";
	$usr = "root";
	$pw = "";
	$db = "mfu";
	$port = "";

	$con = mysqli_connect($host, $usr, $pw, $db);
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	session_start();
	
	if(!isset($_SESSION['c'])){
		$_SESSION['c']=0;
	}
	
	if(!isset($_SESSION['login'])){
		$_SESSION['login']='GUEST';
	}
	
	if(!isset($_SESSION['lgid'])){
		$_SESSION['lgid']=0;
	}
	
	//--------------- Privilege -------------------//
	$allowadd = array(8,9);
	$allowdelete = array(8,9);
	$allowupdate = array(8,9);
	$showaccount = array(0,1,9);
	$addproducttocart = array(1,9);
	//---------------------------------------------//
	
	if(!isset($_SESSION['shopping_cart'])){
		echo "<script>window.location='/mfu/products.php'</script>";
	}
	
	if(isset($_GET['action'])){
		if($_GET['action'] == "delete"){
			foreach($_SESSION["shopping_cart"] as $keys => $values){
				if($values["item_id"] == $_GET['id']){
					unset($_SESSION["shopping_cart"][$keys]);
					echo '<script>alert("Item is Removed");</script>';
					if($_SESSION["shopping_cart"] != Array()){
						echo '<script>window.location="PaymentForm.php";</script>';
					}else{
						echo '<script>window.location="products.php";</script>';
					}
				}
			}
		}
		else if($_GET['action'] == "deleteall"){
			unset($_SESSION["shopping_cart"]);
			echo '<script>alert("Items are Removed");</script>';
			echo '<script>window.location="products.php";</script>';
		}
	}
	
	$lgid = $_SESSION['lgid'];
	
	$sql1 = "SELECT * FROM address WHERE user_id='".$_SESSION['lgid']."' AND current='1'";
	$sql2 = "SELECT * FROM creditcard WHERE user_id='".$_SESSION['lgid']."' AND current='1'";
	
	$qry1 = mysqli_query($con,$sql1);
	$qry2 = mysqli_query($con,$sql2);
	
	$result1 = mysqli_fetch_array($qry1);
	$result2 = mysqli_fetch_array($qry2);
	
	if($result1 != 0){
		$daddrid = $result1['id'];
		$dprovince = $result1['province'];
		$ddistrict = $result1['district'];
		$dsubdistrict = $result1['subdistrict'];
		$dlocation = $result1['location'];
		$dzipcode = $result1['zipcode'];
		$dtelephone = $result1['telephone'];
	}else{
		$dprovince = "No Information";
		$ddistrict = "No Information";
		$dsubdistrict = "No Information";
		$dlocation = "No Information";
		$dzipcode = "No Information";
		$dtelephone = "No Information";
	}
	if($result2 != 0){
		$dpayid = $result2['id'];
		$dcardno = $result2['cardnumber'];
		$dholder = $result2['holder_name'];
		//$dexp = $result2['exp'];
		//$dpin = $result2['pin'];
	}else{
		$dcardno = "No Information";
		$dholder = "No Information";
		//$dexp = "NULLSPACE";
		//$dpin = "NULLSPACE";
	}
	
	$alltotal=0;
	
	
	if(isset($_POST['submit'])){
		$peraddress = 'false';
		$perpay = 'false';
		$progress = true;
		if($_POST['radioaddress']==1){
			if($dprovince == "No Information" || $ddistrict == "No Information" || $dsubdistrict == "No Information" || $dlocation == "No Information" || $dzipcode == "No Information" || $dtelephone == "No Information"){
				$progress = false;
				echo '<script>alert("Please Use Another Address!")</script>';
			}else{
				$peraddress = 'false';
			}
		}
		else if($_POST['radioaddress']==2){
			if($_POST['newaddr'] == "" || $_POST['newprov'] == "" || $_POST['newdist'] == "" || $_POST['newsdist'] == "" || $_POST['newzip'] == "" || $_POST['newtel'] == ""){
				$progress = false;
				echo "<script>alert('Please Fill All In ADDRESS Form')</script>";
			}
			else{
				$location = $_POST['newaddr'];
				$province = $_POST['newprov'];
				$district = $_POST['newdist'];
				$subdist = $_POST['newsdist'];
				$zipcode = $_POST['newzip'];
				$teladdr = $_POST['newtel'];
				if(isset($_POST['setaddr'])){	
					$clearqry = "UPDATE address SET current='0' WHERE user_id='$lgid' AND current='1'";
					mysqli_query($con, $clearqry);
					$qry2 = "INSERT INTO address VALUES ('','$lgid','$location','$subdist','$district','$province','$zipcode','$teladdr','1')";
				}
				else{
					$qry2 = "INSERT INTO address VALUES ('','$lgid','$location','$subdist','$district','$province','$zipcode','$teladdr','0')";
				}
				mysqli_query($con, $qry2);
			}
			$using1 = mysqli_query($con, "SELECT * FROM address WHERE user_id='$lgid' 
																		AND location='$location' 
																		AND subdistrict='$subdist' 
																		AND province='$province' 
																		AND zipcode='$zipcode' 
																		AND telephone='$teladdr' 
																LIMIT 1");
			$usingresult1 = mysqli_fetch_array($using1);
			$usingaddress = $usingresult1['id'];
			$peraddress = 'true';
		}
		$prom = 0;
		if($_POST['radiopay']==1){
			if($dcardno == "No Information" || $dholder == "No Information"){
				$progress = false;
				echo '<script>alert("Please Use Another Payment Method!")</script>';
			}else{
				$perpay = 'false';
			}
		}else if($_POST['radiopay']==2){
			if($_POST['cdnum1'] == "" || $_POST['cdnum2'] == "" || $_POST['cdnum3'] == "" || $_POST['cdnum4'] == "" || $_POST['holder'] == ""){
				$progress = false;
				echo "<script>alert('Please Fill All In Payment Form')</script>";
			}else{
				$newcardno = $_POST['cdnum1'].$_POST['cdnum2'].$_POST['cdnum3'].$_POST['cdnum4'];
				$newholder = $_POST['holder'];
				//$newcardexp = $_POST['cardexp'];
				//$newcardpin = $_POST['cardpin'];
				if(isset($_POST['setcard'])){
					$clearqry = "UPDATE creditcard SET current='0' WHERE user_id='$lgid' AND current='1'";
					mysqli_query($con, $clearqry);
					$qry3 = "INSERT INTO creditcard VALUES ('','$lgid','$newholder','$newcardno','','','','','1')";					
				}else{
					$qry3 = "INSERT INTO creditcard VALUES ('','$lgid','$newholder','$newcardno','','','','','0')";
				}
				mysqli_query($con, $qry3);
			}
			$using2 = mysqli_query($con, "SELECT * FROM creditcard WHERE user_id='$lgid' 
																		AND holder_name='$newholder'  
																		AND cardnumber='$newcardno' 
																	LIMIT 1");
			$usingresult2 = mysqli_fetch_array($using2);
			$usingpay = $usingresult2['id'];
			$perpay = 'true';
		}else if($_POST['radiopay'] == 3){
			$dpayid = 0;
			$usingpay = 0;
			$prom = 1;
		}
		
		if($progress == true){
			$error = false;
			if($peraddress == 'false' && $perpay == 'false'){
				$sql = "INSERT INTO receipt VALUES ('','$lgid','$daddrid','$dpayid','0','','0','0','$prom')";
			}elseif($peraddress == 'true' && $perpay == 'false'){
				$sql = "INSERT INTO receipt VALUES ('','$lgid','$usingaddress','$dpayid','0','','0','0','$prom')";
			}elseif($peraddress == 'false' && $perpay == 'true'){
				$sql = "INSERT INTO receipt VALUES ('','$lgid','$daddrid','$usingpay','0','','0','0','$prom')";
			}elseif($peraddress == 'true' && $perpay == 'true'){
				$sql = "INSERT INTO receipt VALUES ('','$lgid','$usingaddress','$usingpay','0','','0','0','$prom')";
			}else{
				$error = true;
				echo "<script>Insert False</script>";
			}
			if($error == false){
				$qry = mysqli_query($con,$sql);
				if($qry){
					$last_id = mysqli_insert_id($con);
					echo '<script>alert("Your Receipt ID = '.$last_id.'")</script>';
				}
				$searchsql = "SELECT id FROM receipt WHERE user_id='$lgid' AND producted=0";
				$searchqry = mysqli_query($con,$searchsql);
				$searchresult = mysqli_fetch_array($searchqry);
				$receiptid = $searchresult['id'];
				foreach($_SESSION['shopping_cart'] as $key => $values){
					$checksql = "SELECT * FROM product WHERE id=".$values["item_id"];
					$resultcheck = mysqli_query($con,$checksql);
					$thisrow = mysqli_fetch_array($resultcheck);
					if($thisrow['instock'] >= $values['item_quantity']){
						$subsql = "INSERT INTO lineitems VALUES ('',
																	'$receiptid',
																	'".$values['item_id']."',
																	'".$values["item_quantity"]."',
																	'".($values["item_quantity"] * $values["item_price"])."'
																)";
						mysqli_query($con,$subsql);
						$alltotal = $alltotal + ($values["item_quantity"] * $values["item_price"]);
						$descsql = "UPDATE product SET instock=instock-".$values["item_quantity"]." WHERE id=".$values["item_id"];
						mysqli_query($con,$descsql);
					}else{
						echo "<script>alert('RUN OUT OF SOME PRODUCT')</script>";
					}
				}
				$successsql = "UPDATE receipt SET subtotal='$alltotal', producted=1 WHERE user_id='$lgid' AND producted=0";
				mysqli_query($con,$successsql);
				unset($_SESSION['shopping_cart']);
				echo "<script>alert('Confirmed')</script>";
				echo "<script>window.location='/mfu/products.php'</script>";
			}else{
				echo "<script>alert('Somethings went wrong!')</script>";
			}
		}else{
			echo "<script>alert('Invalid Information!')</script>";
		}
	}
	
	if(isset($_POST['test'])){
		echo "<script>alert('".$_POST['setaddr']."')</script>";
		
	}
?>




<!DOCTYPE html>
<html lang="en">
<head>
	<title>Payment</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/themify/themify-icons.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/elegant-font/html-css/style.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body class="animsition">

	<!-- Header -->
	<header class="header1">
		<!-- Header desktop -->
		<div class="container-menu-header">
			<div class="topbar">
				<div class="topbar-social">
					<a href="/mfu/home.php" class="topbar-social-item">9elan Co.,Ltd.</a>
					<!--<a href="#" class="topbar-social-item fa fa-facebook"></a>
					<a href="#" class="topbar-social-item fa fa-instagram"></a>
					<a href="#" class="topbar-social-item fa fa-pinterest-p"></a>
					<a href="#" class="topbar-social-item fa fa-snapchat-ghost"></a>
					<a href="#" class="topbar-social-item fa fa-youtube-play"></a>-->
				</div>

				<span class="topbar-child1">
					Free shipping for standard order over ฿300
				</span>

				<div class="topbar-child2">
					<span class="topbar-email">
						9elan.company@gmail.com
					</span>
				</div>
			</div>

			<div class="wrap_header">
				<!-- Logo -->
				<a href="#" class="logo">
					<img src="/mfu/myimages/mylogo.png">
				</a>

				<!-- Menu -->
				<div class="wrap_menu">
					<nav class="menu">
						<ul class="main_menu">
							
							<li>
								<a href="home.php">Home</a>
							</li>

							<li>
								<a href="products.php">Shop</a>
							</li>

							<!--<li>
								<a href="cart.html">Cart</a>
							</li>-->

							<li>
								<a href="blog.html">News</a>
							</li>

							<li>
								<a href="about.html">About us</a>
							</li>

							<li>
								<a href="contact.php">Contact</a>
							</li>
						</ul>
					</nav>
				</div>

				<!-- Header Icon -->
				<div class="header-icons">
					<?php 
						if($_SESSION['login']!='GUEST' && $_SESSION['c']>6):
					?>
					<div class="header-wrapicon1">
						<a class="header-icon1 js-show-header-dropdown"><?php echo $_SESSION['login']."&nbsp;&nbsp;";?></a>
						<img src="images/icons/icon-header-01.png" class="header-icon1 js-show-header-dropdown" alt="ICON">
						<div class="header-cart header-dropdown">
							<ul class="header-cart-wrapitem">
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">	
										<center>
											<b class="m-text14">MY ACCOUNT</b>
										</center>
									</div>
								</li>
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/products.php?action=logout" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												LOG OUT
											</a>
										</center>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<?php 
						elseif($_SESSION['login']!='GUEST' && $_SESSION['c']!='0'):	
					?>
					<div class="header-wrapicon1">
						<a class="header-icon1 js-show-header-dropdown"><?php echo $_SESSION['login']."&nbsp;&nbsp;";?></a>
						<img src="images/icons/icon-header-01.png" class="header-icon1 js-show-header-dropdown" alt="ICON">
						<div class="header-cart header-dropdown">
							<ul class="header-cart-wrapitem">

								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">	
										<center>
											<b class="m-text14">MY ACCOUNT</b>
										</center>
									</div>
								</li>
								
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/customerorder.php" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												ORDER HISTORY
											</a>
										</center>
									</div>
								</li>

								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/editaccount.php" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												EDIT ACCOUNT
											</a>
										</center>
									</div>
								</li>

								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/products.php?action=logout" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												LOG OUT
											</a>
										</center>
									</div>
								</li>
								
							</ul>
						</div>
					</div>
					<?php
						else:
					?>
					<button onclick='document.getElementById("loginbox").style.display="block"'>Login&nbsp;&nbsp;</button>
					<a class="header-wrapicon1 dis-block">
						<img src="images/icons/icon-header-01.png" class="header-icon1" alt="ICON" onclick='document.getElementById("loginbox").style.display="block"'>
					</a>
					<?php endif;?>
					
					

					<?php
						if(in_array($_SESSION['c'],$addproducttocart)):
					?>
					
					<span class="linedivide1"></span>

					<div class="header-wrapicon2">
						<img src="images/icons/icon-header-02.png" class="header-icon1 js-show-header-dropdown" alt="ICON">
						<span class="header-icons-noti"><?php 
																if(isset($_SESSION['shopping_cart']))echo count($_SESSION['shopping_cart']);
																else echo "0";
														?>
						</span>

						<!-- Header cart noti -->
						<div class="header-cart header-dropdown">
									<li class="header-cart-wrapitem header-cart-item">
										<div class="header-cart-item-mytxt">	
											<center>
												<b class="m-text14">MY CART</b>
											</center>
										</div>
									</li>
							<?php if(!empty($_SESSION["shopping_cart"])):?>
								<ul class="header-cart-wrapitem">
									<?php 	$total = 0;
											foreach($_SESSION['shopping_cart'] as $key => $values):
									?>
										<li class="header-cart-item">
											<div class="header-cart-item-img" onclick="location.href='/mfu/products.php?action=delete&id=<?php echo $values["item_id"];?>';">
												<img src="/mfu/productimages/<?php echo $values['item_img'];?>">
											</div>

											<div class="header-cart-item-txt">
												<a href="/mfu/productdetail.php?id=<?php echo $values["item_id"];?>" class="header-cart-item-name">
													<?php echo $values["item_name"];?>
												</a>

												<span class="header-cart-item-info">
													<?php echo $values["item_quantity"];?> x ฿ <?php echo number_format($values["item_price"]);?> 
													= ฿ <?php echo number_format($values["item_quantity"] * $values["item_price"]);?>
												</span>
											</div>
										</li>
									<?php 	$total = $total + ($values["item_quantity"] * $values["item_price"]);
											endforeach;
									?>
								</ul>
								<div class="header-cart-total">
									Total: ฿ <?php echo number_format($total);?>
								</div>
								<div class="header-cart-buttons">
									<div class="header-cart-wrapbtn">
										<!-- Button -->
										<a href="/mfu/products.php?action=deleteall" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
											Clear Cart
										</a>
									</div>

									<div class="header-cart-wrapbtn">
										<!-- Button -->
										<a href="/mfu/payment.php" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
											Check Out
										</a>
									</div>
								</div>
							<?php else:?>
								<a class="header-cart-item-name">
									No item
								</a>
							<?php endif;?>
						</div>
					</div>
					<?php endif;?>
				</div>
			</div>
		</div>

		<div>
			<!-- Header Mobile -->
			<div class="wrap_header_mobile">
				<!-- Logo moblie -->
				<a href="#" class="logo-mobile">
					<img src="/mfu/myimages/mylogo.png">
				</a>

				<!-- Button show menu -->
				<div class="btn-show-menu">
					<!-- Header Icon mobile -->
					<div class="header-icons-mobile">
						<?php 
						if($_SESSION['login']!='GUEST' && $_SESSION['c']>6):
					?>
					<div class="header-wrapicon1">
						<img src="images/icons/icon-header-01.png" class="header-icon1 js-show-header-dropdown" alt="ICON">
						<div class="header-cart header-dropdown">
							<ul class="header-cart-wrapitem">
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">	
										<center>
											<b class="m-text14">MY ACCOUNT</b>
										</center>
									</div>
								</li>
								
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">	
										<center>
											<a>Account : <?php echo $_SESSION['login'];?></a>
										</center>
									</div>
								</li>
								
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/products.php?action=logout" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												LOG OUT
											</a>
										</center>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<?php 
						elseif($_SESSION['login']!='GUEST' && $_SESSION['c']!='0'):	
					?>
					<div class="header-wrapicon1">
						<img src="images/icons/icon-header-01.png" class="header-icon1 js-show-header-dropdown" alt="ICON">
						<div class="header-cart header-dropdown">
							<ul class="header-cart-wrapitem">

								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">	
										<center>
											<b class="m-text14">MY ACCOUNT</b>
										</center>
									</div>
								</li>
								
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">	
										<center>
											<a>Account : <?php echo $_SESSION['login'];?></a>
										</center>
									</div>
								</li>
								
								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/customerorder.php" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												ORDER HISTORY
											</a>
										</center>
									</div>
								</li>

								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/editaccount.php" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												EDIT ACCOUNT
											</a>
										</center>
									</div>
								</li>

								<li class="header-cart-item">
									<div class="header-cart-item-mytxt">
										<center>
											<a href="/mfu/products.php?action=logout" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												LOG OUT
											</a>
										</center>
									</div>
								</li>
								
							</ul>
						</div>
					</div>
					<?php
						else:
					?>
					<a class="header-wrapicon1 dis-block">
						<img src="images/icons/icon-header-01.png" class="header-icon1" alt="ICON" onclick='document.getElementById("loginbox").style.display="block"'>
					</a>
					<?php endif;?>

						<?php
							if(in_array($_SESSION['c'],$addproducttocart)):
						?>
						
						<span class="linedivide2"></span>

						<div class="header-wrapicon2">
							<img src="images/icons/icon-header-02.png" class="header-icon1 js-show-header-dropdown" alt="ICON">
							<span class="header-icons-noti"><?php 
																	if(isset($_SESSION['shopping_cart']))echo count($_SESSION['shopping_cart']);
																	else echo "0";
															?>
							</span>

							<!-- Header cart noti -->
							<div class="header-cart header-dropdown">
										<li class="header-cart-wrapitem header-cart-item">
											<div class="header-cart-item-mytxt">	
												<center>
													<b class="m-text14">MY CART</b>
												</center>
											</div>
										</li>
								<?php if(!empty($_SESSION["shopping_cart"])):?>
									<ul class="header-cart-wrapitem">
										<?php 	$total = 0;
												foreach($_SESSION['shopping_cart'] as $key => $values):
										?>
											<li class="header-cart-item">
												<div class="header-cart-item-img" onclick="location.href='/mfu/products.php?action=delete&id=<?php echo $values["item_id"];?>';">
													<img src="/mfu/productimages/<?php echo $values['item_img'];?>">
												</div>

												<div class="header-cart-item-txt">
													<a href="/mfu/productdetail.php?id=<?php echo $values['item_id'];?>" class="header-cart-item-name">
														<?php echo $values["item_name"];?>
													</a>

													<span class="header-cart-item-info">
														<?php echo $values["item_quantity"];?> x ฿ <?php echo number_format($values["item_price"]);?> 
														= ฿ <?php echo number_format($values["item_quantity"] * $values["item_price"]);?>
													</span>
												</div>
											</li>
										<?php 	$total = $total + ($values["item_quantity"] * $values["item_price"]);
												endforeach;
										?>
									</ul>
									<div class="header-cart-total">
										Total: ฿ <?php echo number_format($total);?>
									</div>
									<div class="header-cart-buttons">
										<div class="header-cart-wrapbtn">
											<!-- Button -->
											<a href="/mfu/products.php?action=deleteall" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												Clear Cart
											</a>
										</div>

										<div class="header-cart-wrapbtn">
											<!-- Button -->
											<a href="/mfu/payment.php" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
												Check Out
											</a>
										</div>
									</div>
								<?php else:?>
									<a class="header-cart-item-name">
										No item
									</a>
								<?php endif;?>
							</div>
						</div>
						<?php endif;?>
					</div>

					<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</div>
				</div>
			</div>

			<!-- Menu Mobile -->
			<div class="wrap-side-menu">
				<nav class="side-menu">
					<ul class="main-menu">
						<li class="item-topbar-mobile p-l-20 p-t-8 p-b-8">
							<span class="topbar-child1">
								Free shipping for standard order over $100
							</span>
						</li>

						<li class="item-topbar-mobile p-l-20 p-t-8 p-b-8">
							<div class="topbar-child2-mobile">
								<span class="topbar-email">
									9elan.company@gmail.com
								</span>
							</div>
						</li>

						<li class="item-topbar-mobile p-l-10">
							<div class="topbar-social-mobile">
								<a href="#" class="topbar-social-item fa fa-facebook"></a>
								<a href="#" class="topbar-social-item fa fa-instagram"></a>
								<a href="#" class="topbar-social-item fa fa-pinterest-p"></a>
								<a href="#" class="topbar-social-item fa fa-snapchat-ghost"></a>
								<a href="#" class="topbar-social-item fa fa-youtube-play"></a>
							</div>
						</li>
						
						
						<li class="item-menu-mobile">
							<a href="Home.php">Home</a>
						</li>
						
						<li class="item-menu-mobile">
							<a href="products.php">Shop</a>
							<ul class="sub-menu">
								<li><a href="products.php">Adenoscence</a></li>
								<li><a href="products.php">Shop2</a></li>
								<li><a href="products.php">Shop3</a></li>
							</ul>
							<i class="arrow-main-menu fa fa-angle-right" aria-hidden="true"></i>
						</li>

						<!--<li class="item-menu-mobile">
							<a href="cart.html">Cart</a>
						</li>

						<li class="item-menu-mobile">
							<a href="blog.html">Blog</a>
						</li>

						<li class="item-menu-mobile">
							<a href="about.html">About</a>
						</li>

						<li class="item-menu-mobile">
							<a href="contact.html">Contact</a>
						</li>-->
					</ul>
				</nav>
			</div>
		</div>
	</header>
	
	<div id="paybox" class="mymodal">
		<div class="mymodal-content myanimate">
			<div class="newcontainer">
				<p align="right" style="position:relative">
					<span onclick="document.getElementById('paybox').style.display='none'" class="myclose" title="Close">&times;</span>
				</p>
				<center><br/>
				<font size="11">
					<b>PromptPay</b>
				</font><br/>
				</center>
				
				<div class="p-l-10">
				
					Phone Number : 080 - 000 - 0000<br/>
					
					- You'll receive Receipt ID after push Submit<br/>
					
					- Pay to above phone number<br/>
					
					- Send picture of your payment script to 9elan.company@gmail.com<br/>
					
					(Subject with your Receipt ID)<br/>
					
					<center>
					
					<br/>
					<div class="m-text14">ex. Receipt ID</div><br/>
					
					<img src="/mfu/myimages/htpromp/1.png" style="max-width:500px; max-height:500px"></img><br/>
					
					<br/>
					<div class="m-text14">ex. History</div><br/>
					
					<img src="/mfu/myimages/htpromp/3.png" style="max-width:500px; max-height:500px"></img><br/>
					
					<img src="/mfu/myimages/htpromp/4.png" style="max-width:500px; max-height:500px"></img><br/>
					
					<br/>
					<div class="m-text14">ex. Send Email</div><br/>
					<img src="/mfu/myimages/htpromp/2.png" style="max-width:500px; max-height:500px"></img><br/>
					</center>
				</div>
				
				<br/><br/>
			</div>
		</div>
	</div>


	
	
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>Payment and Shipping</title>
	<body>
	<br/>
		<center>
		<h1> Payment & Shipping </h1>
		<br/>
		<h4>
		<?php if(isset($_SESSION['login'])){
			echo "Account : ".$_SESSION['login'];
		}?></h4><br/>
		<div class="p-b-10">
			<h2>Item List</h2>
		</div>
		<div class="container-table-cart pos-relative">
			<div class="wrap-table-shopping-cart bgwhite">
				<table border="1" width="80%" style="max-width:1000px" class="table-shopping-cart2">
					<tr>
						<th width="200px" height="30px"><center>Item Name</th>
						<th width="100px"><center>Quantity</th>
						<th width="150px"><center>Price</th>
						<th width="150px"><center>Total</th>
						<th width="80px"><center>Action</th>
					</tr>
					<?php
					if(!empty($_SESSION["shopping_cart"])):
						$total = 0;
						foreach($_SESSION["shopping_cart"] as $key => $values):
					?>
					<tr>
						<td><center><a href="/mfu/UpdateForm.php?id=<?php echo $values['item_id'];?>"><?php echo $values["item_name"];?></a></td>
						<td><center><?php echo $values["item_quantity"];?></td>
						<td><center><?php echo number_format($values["item_price"]);?></td>
						<td><center><?php echo number_format($values["item_quantity"] * $values["item_price"]);?></td>
						<td><center><a href="/mfu/PaymentForm.php?action=delete&id=<?php echo $values['item_id'];?>">Remove</a></td>
					</tr>
					<?php
							$total = $total + ($values["item_quantity"] * $values["item_price"]);
						endforeach;
					?>
					<tr>
						<td colspan="3" align="right">Total :&nbsp;</td>
						<td align="center">฿ <?php echo number_format($total,2)?>
						<td align="center"><a href="/mfu/products.php?action=deleteall">All</a></td>
					</tr>
					<?php endif; ?>
				</table>
			</div>
		</div>
		<br/>
		<form method="post" action="">
			<div class="p-b-10">
				<h2>Shipping Address</h2>
			</div>
			
			<div class="container-table-cart pos-relative">
				<div class="wrap-table-shopping-cart bgwhite">
					<table border=1 width="80%" style="max-width:1000px" class="table-shopping-cart2">
						<fieldset style="border:0;" id="thisischoice">
							<tr>
								<td width="45%" valign="top" class="p-l-10">
									<input onclick="aold();" type="radio" name="radioaddress" value="1" checked>&nbsp;Default</input>
								</td>
								<td width="55%" class="p-l-10">
									<input onclick="anew();" type="radio" name="radioaddress" value="2">&nbsp;New Address</input>
								</td>
							</tr>
						</fieldset>
						<tr>
							<td align="center">
								<table width="90%">
									<tr>
										<td align="right" width="40%"><b>Province&nbsp;</b></td>
										<td><?php echo $dprovince;?></td>
										<input type="hidden" name="hidden_province" value="<?php echo $dprovince;?>"></input>
									</tr>
									<tr>
										<td colspan=2 align="center"><div class="mylinedivide1"></div></td>
									</tr>
									<tr>
										<td align="right"><b>District&nbsp;</b></td>
										<td><?php echo $ddistrict;?></td>
										<input type="hidden" name="hidden_district" value="<?php echo $ddistrict;?>"></input>
									</tr>
									<tr>
										<td colspan=2 align="center"><div class="mylinedivide1"></div></td>
									</tr>
									<tr>
										<td align="right"><b>Sub - District&nbsp;</b></td>
										<td><?php echo $dsubdistrict;?></td>
										<input type="hidden" name="hidden_subdistrict" value="<?php echo $dsubdistrict;?>"></input>
									</tr>
									<tr>
										<td colspan=2 align="center"><div class="mylinedivide1"></div></td>
									</tr>
									<tr>
										<td valign="top" align="right"><b>Address&nbsp;</b></td>
										<td><?php echo $dlocation;?></td>
										<input type="hidden" name="hidden_location" value="<?php echo $dlocation;?>"></input>
									</tr>
									<tr>
										<td colspan=2 align="center"><div class="mylinedivide1"></div></td>
									</tr>
									<tr>
										<td align="right"><b>Zip Code&nbsp;</b></td>
										<td><?php echo $dzipcode;?></td>
										<input type="hidden" name="hidden_zip" value="<?php echo $dzipcode;?>"></input>
									</tr>
									<tr>
										<td colspan=2 align="center"><div class="mylinedivide1"></div></td>
									</tr>
									<tr>
										<td align="right"><b>Tel.&nbsp;</b></td>
										<td><?php echo $dtelephone;?></td>
										<input type="hidden" name="hidden_telephone" value="<?php echo $dtelephone;?>"></input>
									</tr>
								</table>
							</td>
							<td>
								<fieldset style="border:0px" id="addrnew" disabled="true">
									<table width="95%">
										<tr><div class="p-t-5">
											<td align="right" width="30%" class="p-r-5"><b>Province</b></td>
											<td width="70%" height="30px">
												<div class="bo4">
													<input type="text" name="newprov" style="width:100%" class="p-l-5"></input>
												</div>
											</td>
										</div></tr>
										<tr>
											<td align="right" class="p-r-5"><b>District</b></td>
											<td height="30px">
												<div class="bo4">
													<input type="text" name="newdist" style="width:100%" class="p-l-5"></input>
												</div>
											</td>
										</tr>
										<tr>
											<td align="right" class="p-r-5"><b>Sub-District</b></td>
											<td height="30px">
												<div class="bo4">
													<input type="text" name="newsdist" style="width:100%" class="p-l-5"></input>
												</div>
											</td>
										</tr>
										<tr>
											<td valign="top" align="right" class="p-t-5 p-r-5"><b>Address</b></td>
											<td valign="center" height="150px">
												<div class="bo4">
													<textarea type="text" name="newaddr" style="width:100%; border:0px;" rows="5" class="p-l-5"></textarea>
												</div>
											</td>
										</tr>
										<tr>
											<td align="right" class="p-r-5"><b>Zip Code</b></td>
											<td height="30px"><div class="bo4"><input type="text" name="newzip" style="width:100%" class="p-l-5" maxlength="5"></input></div></td>
										</tr>
										<tr>
											<td align="right" class="p-r-5"><b>Tel.</b></td>
											<td height="30px"><div class="bo4"><input type="text" name="newtel" style="width:100%" class="p-l-5" maxlength="10"></input></div></td>
											
										</tr>
										<tr>
											<td colspan=2 class="p-l-15"><input type="checkbox" name="setaddr">Set to Default</td>
										</tr>
										<tr height="20px"><td></td></tr>
									</table>
								</fieldset>
							</td>
						</tr>
					</table>
				</div>
			</div>
				
				<br/>
			
			<div class="p-b-10">
				<h2>Payment Method</h2>
			</div>
			
			<div class="container-table-cart pos-relative">
				<div class="wrap-table-shopping-cart bgwhite">
					<table border=1 width="80%" style="max-width:1000px" class="table-shopping-cart2">
						<fieldset id="thisispay" style="border:0px">
						<tr>
						
							<td class="p-l-10">
								<input onclick="pay3();" 
										type="radio" name="radiopay" value="3" checked>&nbsp;PromptPay</input>
							</td>

							<td width="35%" valign="top" class="p-l-10" style="background-color: #888888;">
								<input onclick="pay1();" 
										type="radio" name="radiopay" value="1" disabled>&nbsp;Default (Coming Soon)</input>
							</td>
							<td width="35%" class="p-l-10" style="background-color: #888888;">
								<input onclick="pay2();" 
										type="radio" name="radiopay" value="2" disabled>&nbsp;New Payment Method (Coming Soon)</input>
							</td>
						
							
							
						</tr>
						</fieldset>
						<tr>
							<td align="center" width="20%">
								<fieldset id="prompay" style="border:0px">
									<div class="hovchar trans-0-4"><a onclick='document.getElementById("paybox").style.display="block"' >More Information</a></div>
								</fieldset>
							</td>
							<td align="center" style="background-color: #888888;">
								<fieldset id="payold" style="border:0px">
									<table>
										<tr>
											<td align="right">Card NO. :</td>
											<td><?php echo "&nbsp;".substr($dcardno,0,4)." - ".substr($dcardno,4,4)." - ".substr($dcardno,8,4)." - ".substr($dcardno,12,4);?></td>
											<input type="hidden" name="hidden_cardno" value="<?php echo $dcardno;?>"></input>
										</tr>
										<tr>
											<td colspan=2 align="center"><div class="mylinedivide1"></div></td>
										</tr>
										<tr>
											<td align="right">Holder Name :</td>
											<td><?php echo "&nbsp;".$dholder;?></td>
											<input type="hidden" name="hidden_holder" value="<?php echo $dholder;?>"></input>
										</tr>
										<!--<tr>
											<td align="right">EXP. Date :</td>
											<td><textarea type="text" name="dcardexp" rows=1 cols=5 placeholder="mm/yy"></textarea></td>
										</tr>
										<tr>
											<td valign="top" align="right">PIN :</td>
											<td><textarea type="text" name="dcardpin" rows=1 cols=5 placeholder="XXX"></textarea></td>
										</tr>-->
									</table>
								</fieldset>
							</td>
							<td style="background-color: #888888;">
								<fieldset id="paynew" style="border:0px" disabled="true">
									<table width="90%">
										<tr height="30px">
											<td align="right" width="30%">Card NO. :&nbsp;</td>
											<td>
												<div class="flex-l-m">
													<div class="bo4 w-sizecredit">
														<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum1" placeholder="xxxx" required></input>
													</div>
													&nbsp;-&nbsp;
													<div class="bo4 w-sizecredit">
														<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum2" placeholder="xxxx" required></input>
													</div>
													&nbsp;-&nbsp;
													<div class="bo4 w-sizecredit">
														<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum3" placeholder="xxxx" required></input>
													</div>
													&nbsp;-&nbsp;
													<div class="bo4 w-sizecredit">
														<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum4" placeholder="xxxx" required></input>
													</div>
												</div>
											</td>
										</tr>
										<tr height="30px">
											<td align="right" width="50%">Holder Name :&nbsp;</td>
											<td><div class="bo4"><input type="text" name="holder" class="p-l-5" style="width:100%" placeholder="Full Name"></input></div></td>
										</tr>
										<!--<tr>
											<td align="right">EXP. Date :</td>
											<td><textarea type="text" name="cardexp" rows=1 cols=5 placeholder="mm/yy"></textarea></td>
										</tr>
										<tr>
											<td valign="top" align="right">PIN :</td>
											<td><textarea type="text" name="cardpin" rows=1 cols=5 placeholder="XXX"></textarea></td>
										</tr>-->
										<tr height="30px">
											<td colspan=2 class="p-l-20"><input type="checkbox" name="setcard" id="testing">Set to Default</td>
										</tr>
									</table>
								</fieldset>
							</td>
							
						</tr>
					</table>
				</div>
			</div>		
			<br/>
			
			<table>
				<tr>
					<td width="100px" align="center"><button class="w-size1 flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" type="submit" name="submit">Submit</button></td>
					<td width="30px"></td>
					<td width="100px" align="center"><button class="w-size1 flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" type="button" onclick="window.history.back();">Back</button></td>
					<!------------------------------------  WAITING FOR LINK ^^^^ ----------------------->
				</tr>
			</table><br/><br/>
		</form>
		</center>
		
		
		
		
		<!--==========================================================================================-->
		<!--==========================================================================================-->
		

	<!-- Footer -->
	<footer class="bg6 p-t-45 p-b-43 p-l-45 p-r-45">
		<div class="flex-w p-b-90">
			<div class="w-size6 p-t-30 p-l-15 p-r-15 respon3">
				<h4 class="s-text12 p-b-30">
					GET IN TOUCH
				</h4>

				<div>
					<p class="s-text7 w-size27">
						Any questions? Let us know in store at 8th floor, 379 Hudson St, New York, NY 10018 or call us on (+1) 96 716 6879
					</p>

					<div class="flex-m p-t-30">
						<a href="#" class="fs-18 color1 p-r-20 fa fa-facebook"></a>
						<a href="#" class="fs-18 color1 p-r-20 fa fa-instagram"></a>
						<a href="#" class="fs-18 color1 p-r-20 fa fa-pinterest-p"></a>
						<a href="#" class="fs-18 color1 p-r-20 fa fa-snapchat-ghost"></a>
						<a href="#" class="fs-18 color1 p-r-20 fa fa-youtube-play"></a>
					</div>
				</div>
			</div>

			<div class="w-size7 p-t-30 p-l-15 p-r-15 respon4">
				<h4 class="s-text12 p-b-30">
					Categories
				</h4>

				<ul>
					<li class="p-b-9">
						<a href="#" class="s-text7">
							Men
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							Women
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							Dresses
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							Sunglasses
						</a>
					</li>
				</ul>
			</div>

			<div class="w-size7 p-t-30 p-l-15 p-r-15 respon4">
				<h4 class="s-text12 p-b-30">
					Links
				</h4>

				<ul>
					<li class="p-b-9">
						<a href="#" class="s-text7">
							Search
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							About Us
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							Contact Us
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							Returns
						</a>
					</li>
				</ul>
			</div>

			<div class="w-size7 p-t-30 p-l-15 p-r-15 respon4">
				<h4 class="s-text12 p-b-30">
					Help
				</h4>

				<ul>
					<li class="p-b-9">
						<a href="#" class="s-text7">
							Track Order
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							Returns
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							Shipping
						</a>
					</li>

					<li class="p-b-9">
						<a href="#" class="s-text7">
							FAQs
						</a>
					</li>
				</ul>
			</div>

			<div class="w-size8 p-t-30 p-l-15 p-r-15 respon3">
				<h4 class="s-text12 p-b-30">
					Newsletter
				</h4>

				<form>
					<div class="effect1 w-size9">
						<input class="s-text7 bg6 w-full p-b-5" type="text" name="email" placeholder="email@example.com">
						<span class="effect1-line"></span>
					</div>

					<div class="w-size2 p-t-20">
						<!-- Button -->
						<button class="flex-c-m size2 bg4 bo-rad-23 hov1 m-text3 trans-0-4">
							Subscribe
						</button>
					</div>

				</form>
			</div>
		</div>

		<div class="t-center p-l-15 p-r-15">
			<a href="#">
				<img class="h-size2" src="images/icons/paypal.png" alt="IMG-PAYPAL">
			</a>

			<a href="#">
				<img class="h-size2" src="images/icons/visa.png" alt="IMG-VISA">
			</a>

			<a href="#">
				<img class="h-size2" src="images/icons/mastercard.png" alt="IMG-MASTERCARD">
			</a>

			<a href="#">
				<img class="h-size2" src="images/icons/express.png" alt="IMG-EXPRESS">
			</a>

			<a href="#">
				<img class="h-size2" src="images/icons/discover.png" alt="IMG-DISCOVER">
			</a>

			<div class="t-center s-text8 p-t-20">
				Copyright © 2018 All rights reserved. | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
			</div>
		</div>
	</footer>



	<!-- Back to top -->
	<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
	</div>

	<!-- Container Selection -->
	<div id="dropDownSelect1"></div>
	<div id="dropDownSelect2"></div>



<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/bootstrap/js/popper.js"></script>
	<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/select2/select2.min.js"></script>
	<script type="text/javascript">
		$(".selection-1").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});

		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect2')
		});
	</script>
<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/slick/slick.min.js"></script>
	<script type="text/javascript" src="js/slick-custom.js"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/sweetalert/sweetalert.min.js"></script>
	<script type="text/javascript">
		$('.block2-btn-addcart').each(function(){
			var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to cart !", "success");
			});
		});

		$('.block2-btn-addwishlist').each(function(){
			var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to wishlist !", "success");
			});
		});

		$('.btn-addcart-product-detail').each(function(){
			var nameProduct = $('.product-detail-name').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to cart !", "success");
			});
		});
		
		function clickaddcart(findid,quantity){
			var x = document.getElementById(findid).value;
			var y = document.getElementById(quantity).value;
			if(x > y){
				swal("ERROR", "This item is sold out !", "error");
			}else{
				swal("SUCCESS", "This item is added to cart !", "success");
			}
		}
		
		function clickaddfail(){
			swal("This item ", "has been already added !", "error");
		}
		
		function clickaddsold(){
			swal("Error", "This item is sold out !", "error");
		}
		
	</script>

<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script>
	
		var boxaddr = document.getElementById('addrnew')
		
		function anew(){
				boxaddr.disabled = false;
			}
			
		function aold(){
				boxaddr.disabled = true;
			}
		
		var box1 = document.getElementById('payold');
		var box2 = document.getElementById('paynew');
		var box3 = document.getElementById('prompay');
		
			function fieldcheck1(input1,input2){
				document.getElementById(input1).disabled = false;
				document.getElementById(input2).disabled = true;
			}
			
			function fieldcheck2(input1,input2){
				document.getElementById(input1).disabled = true;
				document.getElementById(input2).disabled = false;
			}
			
			function pay1(){
				box1.disabled = false;
				box2.disabled = true;
				box3.disabled = true;
			}
			
			function pay2(){
				box1.disabled = true;
				box2.disabled = false;
				box3.disabled = true;
			}
			
			function pay3(){
				box1.disabled = true;
				box2.disabled = true;
				box3.disabled = false;
			}
		var mymodal = document.getElementById('paybox');

		
		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == mymodal) {
				mymodal.style.display = "none";
			}
		}		
		
	</script>

</body>
</html>
