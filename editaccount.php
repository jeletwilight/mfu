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
	
	$allowedit = array(1,8,9);
	
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
				echo "<script>alert('Update Profile Success')</script>";
				echo "<script>window.history.back();</script>";
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
				echo "<script>window.history.back();</script>";
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
				echo "<script>window.history.back();</script>";
			}
		}
	}
	
?>




<!DOCTYPE html>
<html lang="en">
<head>
	<title>Edit Account</title>
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
	
	<div id="profilebox" class="mymodal">
		<form method="post" action="" class="mymodal-content myanimate">
			<div class="newcontainer">
				<p align="right" style="position:relative">
					<span onclick="document.getElementById('profilebox').style.display='none'" class="myclose" title="Close">&times;</span>
				</p>
				<center>
					<br/>
					<font size="11">
						<b>Edit Profile</b>
					</font>
					<br/>
					<table width="90%">
						<tr height="40px">
							<td align="right" class="p-r-5" width="20%">Fullname :</td>
							<td>
								<div class="bo4">
								<input style="width:100%" class="p-l-5" type="text" name="fullname" placeholder="Example: Tester Numberone" value="<?php echo $oldname;?>" required></input>
								</div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">Tel. :</td>
							<td>
								<div class="bo4">
									<input style="width:100%" class="p-l-5 bo4" type="text" name="tel" placeholder="Example: 0812345678" value="<?php echo $oldtelephone;?>" required></input>
								</div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">Other :</td>
							<td>
								<div class="bo4">
									<input style="width:100%" class="p-l-5 bo4" type="text" name="other" placeholder="Example: LineID=userline,Facebook=John Doe" value="<?php echo $oldother;?>"></input>
								</div>
							</td>
						</tr>
						<tr><td height="10"/></tr>	
					</table>
					<table>
						<tr>
							<td width="70%" style="max-width:300px" align="center"><button class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4" type="submit" name="submitprofile">Submit</button></td>
						</tr>
					</table>
					<br/>
				</center>
			</div>
		</form>
	</div>
	
	<div id="addressbox" class="mymodal">
		<form method="post" action="" class="mymodal-content myanimate">
			<div class="newcontainer">
				<p align="right" style="position:relative">
					<span onclick="document.getElementById('addressbox').style.display='none'" class="myclose" title="Close">&times;</span>
				</p>
				<center>
					<br/>
					<font size="11">
						<b>Edit Address<b>
					</font>
					<br/>
					<table width="90%">
						<tr height="40px">
							<td align="right" class="p-r-5" width="35%">Address :</td>
							<td>
								<div class="bo4">
									<input class="p-l-5" style="width:100%" type="text" name="addr" placeholder="Example: 123/1 Moo.1" value="<?php echo $oldlocation;?>" required></input>
								<div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">Province :</td>
							<td>
								<div class="bo4">
									<input class="p-l-5" style="width:100%" type="text" name="prov" placeholder="Example: Chiang Rai" value="<?php echo $oldprovince;?>" required></input>
								<div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">District :</td>
							<td>
								<div class="bo4">
									<input class="p-l-5" style="width:100%" type="text" name="dist" placeholder="Example: Mae Sai" value="<?php echo $olddist;?>" required></input>
								<div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">Sub-District :</td>
							<td>
								<div class="bo4">
									<input class="p-l-5" style="width:100%" type="text" name="sdist" placeholder="Example: Huai Khrai" value="<?php echo $oldsubdist;?>" required></input>
								<div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">Zip Code :</td>
							<td>
								<div class="bo4">
									<input class="p-l-5" style="width:100%" type="text" name="zip" placeholder="Example: 57000" value="<?php echo $oldzip;?>" required></input>
								<div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">Tel. (For Shipping) :</td>
							<td>
								<div class="bo4">
								<input class="p-l-5" style="width:100%" type="text" name="teladdr" placeholder="Example: 0801234567" value="<?php echo $oldteladdr;?>" required></input>
							</td>
						</tr><td height="10"/></tr>	
					</table>
					<table>
						<tr>
							<td width="70%" style="max-width:300px" align="center"><button class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4" type="submit" name="submitaddr">Submit</button></td>
						</tr>
					</table>
					<br/>
				</center>
			</div>
		</form>
	</div>
	
	<div id="paybox" class="mymodal">
		<form method="post" action="" class="mymodal-content myanimate">
			<div class="newcontainer">
				<p align="right" style="position:relative">
					<span onclick="document.getElementById('paybox').style.display='none'" class="myclose" title="Close">&times;</span>
				</p>
				<center>
					<br/>
					<font size="11">
							<b>Edit Payment</b>
					</font>
					<br/>
					<table width="90%">
						<tr height="40px">
							<td align="right" class="p-r-5" width="30%">Holder name : </td>
							<td>
								<div class="bo4">
									<input class="p-l-5" style="width:100%" type="text" name="holder" placeholder="Full Name" value="<?php echo $resultpay['holder_name'];?>" required></input>
								</div>
							</td>
						</tr>
						<tr height="40px">
							<td align="right" class="p-r-5">Card Number : </td>
							<td>
								<div class="flex-l-m">
									<div class="bo4 w-sizecredit">
										<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum1" placeholder="xxxx" value="<?php echo substr($resultpay['cardnumber'],0,4);?>" required></input>
									</div>&nbsp;-&nbsp;
									<div class="bo4 w-sizecredit">
										<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum2" placeholder="xxxx" value="<?php echo substr($resultpay['cardnumber'],4,4);?>" required></input>
									</div>&nbsp;-&nbsp;
									<div class="bo4 w-sizecredit">
										<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum3" placeholder="xxxx" value="<?php echo substr($resultpay['cardnumber'],8,4);?>" required></input>
									</div>&nbsp;-&nbsp;
									<div class="bo4 w-sizecredit">
										<input class="p-l-3" style="width:100%" maxlength="4" type="text" name="cdnum4" placeholder="xxxx" value="<?php echo substr($resultpay['cardnumber'],12,4);?>" required></input>
									</div>
								</div>
							</td>
						</tr>
					</table>
					<br/>
					<table>
						<tr>
							<td width="70%" style="max-width:300px" align="center"><button class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4" type="submit" name="submitpay">Submit</button></td>
						</tr>
					</table>
					<br/>
				</center>
			</div>
		</form>
	</div>
	


	<!-- Edit Account -->
	<center>
	<div class="p-t-20 p-b-30">
		<h1><b>Edit Account</b></h1>
		<br/>
		<h5>Account : <?php echo $usrname;?></h5>
	</div>
	<div class="p-b-10">
		<h2>Profile</h2>
	</div>
	
	<div class="graycontainer">
		<table width="100%">
			<tr>
				<td align="right" class="p-r-5" width="40%">Fullname :</td>
				<td><?php echo $oldname;?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">Tel. :</td>
				<td><?php echo substr($oldtelephone,0,3)." - ".substr($oldtelephone,3,3)." - ".substr($oldtelephone,6,4);?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">Other :</td>
				<td><?php if($oldother!=null)echo $oldother;else echo "No Information";?></td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<button onclick='document.getElementById("profilebox").style.display="block"' class="flex-c-m bg4 bo-rad-23 hov1 s-text1 trans-0-4" style="width:5%; height:40px; min-width:60px;">
			Edit
		</button>
	</div>
	
	<br/><br/>
	
	<div class="p-b-10">	
		<h2>Default Address</h2>
	</div>
	<div class="graycontainer">
		<table>
			<tr>
				<td align="right" class="p-r-5" width="40%">Address :</td>
				<td><?php if($oldlocation!=null)echo $oldlocation;else echo "No Information";?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">Province :</td>
				<td><?php if($oldprovince!=null)echo $oldprovince;else echo "No Information";?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">District :</td>
				<td><?php if($olddist!=null)echo $olddist;else echo "No Information";?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">Sub-District :</td>
				<td><?php if($oldsubdist!=null)echo $oldsubdist;else echo "No Information";?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">Zip Code :</td>
				<td><?php if($oldzip!=null)echo $oldzip;else echo "No Information";?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">Tel. (For Shipping) :</td>
				<td><?php if($oldteladdr!=null)echo substr($oldteladdr,0,3)." - ".substr($oldteladdr,3,3)." - ".substr($oldteladdr,6,4);else echo "No Information";?></td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<button onclick='document.getElementById("addressbox").style.display="block"' class="flex-c-m bg4 bo-rad-23 hov1 s-text1 trans-0-4" style="width:5%; height:40px; min-width:60px;">
			Edit
		</button>
	</div>
		
		<br/><br/>
		
		<div class="p-b-10">
			<h2>Default Payment</h2>
		</div>
		
		<div class="graycontainer">
		<table>
			<tr>
				<td align="right" class="p-r-5">Holder name : </td>
				<td><?php if($resultpay!=null)echo $resultpay['holder_name'];else echo "No Information";?></td>
			</tr>
			<tr>
				<td align="right" class="p-r-5">Card Number : </td>
				<td>
					<?php if($resultpay!=null)
							echo substr($resultpay['cardnumber'],0,4)." - ".
								substr($resultpay['cardnumber'],4,4)." - ".
								substr($resultpay['cardnumber'],8,4)." - ".
								substr($resultpay['cardnumber'],12,4);
						else echo "No Information";?>
				</td>
			</tr>
			<tr><td height="10"/></tr>	
		</table>
		<button onclick='document.getElementById("paybox").style.display="block"' class="flex-c-m bg4 bo-rad-23 hov1 s-text1 trans-0-4" style="width:5%; height:40px; min-width:60px;">
			Edit
		</button>
		
		</div>
		<br/><br/>
		<center><a class="flex-c-m size1ss bg4 bo-rad-23 hov1 s-text1 trans-0-4" href='/mfu/products.php'>Back</a></center>
		<br/><br/>
	</center>
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
	var mymodal1 = document.getElementById('profilebox');
	var mymodal2 = document.getElementById('addressbox');
	var mymodal3 = document.getElementById('paybox');

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == mymodal1) {
				mymodal1.style.display = "none";
			}
			if (event.target == mymodal2) {
				mymodal2.style.display = "none";
			}
			if (event.target == mymodal3) {
				mymodal3.style.display = "none";
			}
		}
	</script>

</body>
</html>
