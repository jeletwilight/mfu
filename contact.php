<?php

	$host = "localhost";
	$usr = "root";
	$pw = "";
	$db = "mfu";
	$port = "";

	$con = mysqli_connect($host, $usr, $pw, $db);
	
	if (mysqli_connect_errno()) {
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
	
	mysqli_query($con,"SET NAMES UTF8");
	
	
	//----------------- Delete Item in cart ------------------//
	
	if(isset($_GET['action'])){
		if($_GET['action'] == "delete"){
			foreach($_SESSION["shopping_cart"] as $keys => $values){
				if($values["item_id"] == $_GET['id']){
					unset($_SESSION["shopping_cart"][$keys]);
					echo '<script>alert("Item is Removed");</script>';
					echo '<script>window.history.back();</script>';
				}
			}
		}
		else if($_GET['action'] == "deleteall"){
			unset($_SESSION["shopping_cart"]);
			echo '<script>alert("All items are Removed");</script>';
			echo '<script>window.history.back();</script>';
		}
		else if($_GET['action'] == "logout"){
			session_destroy();
			echo '<script>alert("LOGGED OUT!");</script>';
			echo '<script>window.location.href="/mfu/products.php";</script>';
		}
	}
	
	//--------------------------------------------------------//
	
	
	if(isset($_POST['login'])){
		$user = $_POST['loginname'];
		$pass = $_POST['loginpsw'];
		
		$sql = "SELECT * FROM login WHERE username='$user' AND password='$pass' LIMIT 1";
		$qry = mysqli_query($con, $sql);
		$result = mysqli_fetch_array($qry);
		
		if($result != false){
			$_SESSION["login"] = $result['username'];
			$_SESSION["c"] = $result['type'];
			$_SESSION["lgid"] = $result['id'];
			echo '<script>window.history.back();</script>';
		}else{
			echo '<script language="javascript">';
			echo 'alert("Wrong ID/Password!")';
			echo '</script>';
			echo '<script>window.history.back();</script>';
		}
	}
	
	//--------------------------------------------------------//
	
	if(isset($_POST['test'])){
		echo "<script type='text/javascript' src='vendor/sweetalert/sweetalert.min.js'></script>",
			 "<script type='text/javascript'>clickaddcart();</script>";
		sleep(2);
	}
?>


<!DOCTYPE html>

<html lang="en">
<head>
	<title>Contact</title>
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
	<link rel="stylesheet" type="text/css" href="vendor/noui/nouislider.min.css">
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
								<a href="contact.html">Contact</a>
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
	
	<div id="loginbox" class="mymodal">
		<form method="post" action="" class="mymodal-content myanimate">
			<div class="newcontainer">
				<p align="right" style="position:relative">
					<span onclick="document.getElementById('loginbox').style.display='none'" class="myclose" title="Close">&times;</span>
				</p>
				<center><font size="11"><b>Login</b></font>
				<br/>
				<table width="80%" style="max-width:500px">
					<tr>
						<td width='30' align="right" style="padding-right:10px"><label for="loginname"><b>Username</b></label></td>
						<td width='70%'><div class="bo4">
							<input style="padding-left:5px; width:100%" type="text" placeholder="Enter Username" name="loginname" required>
						</div></td>
					</tr>
					<br/>
					<tr>
						<td align="right" style="padding-right:10px"><label for="loginpsw"><b>Password</b></label></td>
					
						<td><div class="bo4">
							<input style="padding-left:5px; width:100%" type="password" placeholder="Enter Password" name="loginpsw" required>
						</div></td>
					</tr>
				</table>
					
				<table width="80%" style="max-width:200px">
					<tr>
						<td height="70px" align="center">
							<div>
								<button type="submit" name="login" class="w-size11s flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" value="Login">Login</button>
							</div>
						</td>
						<td align="center">
							<div>
								<a href="/mfu/register.php" class="w-size11s flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4">Register</a>
							</div>
						</td>
					</tr>
				</table>
				
				
				</center>
			</div>
		</form>
	</div>

	<!-- Title Page -->
	<section class="bg-title-page p-t-50 p-b-40 flex-col-c-m" style="background-image: url(myimages/cpattern.png);">
		<h2 class="l-text2 t-center">
			Adenoscence
		</h2>
		<p class="m-text13 t-center">
			Cosmetics Shop
		</p>
	</section>

	<!-- Content page -->
	<section class="bgwhite p-t-55 p-b-65">
		<div class="container">
			<center>
				<table border=1>
					<tr>
						<td align="center">Line</td>
						<td align="center">Email</td>
					</tr>
					<tr>
						<td class="p-l-10 p-r-10 p-b-10"><img src="myimages/linelogo.jpg" style="width:200px; height:200px;"><br/>ID : @end5968v</td>
						<td class="p-l-10 p-r-10">9elan.company@gmail.com</td>
					</tr>
				</table>
			</center>
		</div>
	</section>


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
				<img class="h-size2s" src="images/icons/visa.png" alt="IMG-VISA">
			</a>

			<a href="#">
				<img class="h-size2s" src="images/icons/mastcard.png" alt="IMG-MASTERCARD">
			</a>
			
			<a href="#">
				<img class="h-size2s" src="images/icons/prompay2.jpeg">
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
	<script type="text/javascript" src="vendor/daterangepicker/moment.min.js"></script>
	<script type="text/javascript" src="vendor/daterangepicker/daterangepicker.js"></script>
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
		
		/*$(document).ready(function(){
			$("#addingcart").click(function(){
				swal("This item ", "is added to cart !", "success");
			});
		});*/
		
		function clickaddcart(findid,quantity){
			var x = document.getElementById(findid).value;
			var y = document.getElementById(quantity).value;
			if(x > y){
				swal("ERROR", "This item is sold out !", "error");
			}else{
				swal("SUCCESS", "This item is added to cart !", "success");
			}
		}
		
		function clickaddsuccess(){
			swal("SUCCESS", "This item is added to cart !", "success");
		}
		
		
		function clickaddfail(){
			swal("This item ", "has been already added !", "error");
		}
		
		function clickaddsold(){
			swal("Error", "This item is sold out !", "error");
		}
		

		$('.block2-btn-addwishlist').each(function(){
			var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to wishlist !", "success");
			});
		});
	</script>

<!--===============================================================================================-->
	<!--<script type="text/javascript" src="vendor/noui/nouislider.min.js"></script>
	<script type="text/javascript">
		/*[ No ui ]
	    ===========================================================*/
	    var filterBar = document.getElementById('filter-bar');

	    noUiSlider.create(filterBar, {
	        start: [ 30, 3000 ],
	        connect: true,
	        range: {
	            'min': 30,
	            'max': 3000
	        }
	    });

	    var skipValues = [
	    document.getElementById('value-lower'),
	    document.getElementById('value-upper')
	    ];

	    filterBar.noUiSlider.on('update', function( values, handle ) {
	        skipValues[handle].innerHTML = Math.round(values[handle]) ;
	    });
	</script>-->
<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script>
		$(document).ready(function(){
			$(this).click(function(){
				if($(this).class == "item-pagination flex-c-m trans-0-4"){
					$(this).toggleClass("item-pagination flex-c-m trans-0-4 active-pagination");
				}
			});
		});
		
		var mymodal = document.getElementById('loginbox');

		
		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == mymodal) {
				mymodal.style.display = "none";
			}
		}
		
		/*
		window.onscroll = function() {myFunction()};
		
		var navmobile = document.getElementById("navmobile");
		
		var sticky = navmobile.offsetTop;

		function myFunction() {
		  if (window.pageYOffset >= sticky) {
			navmobile.classList.add("sticky")
		  } else {
			navmobile.classList.remove("sticky");
		  }
		}*/
		
	</script>

</body>
</html>
