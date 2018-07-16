<?php
	$con = mysqli_connect("localhost", "root", "", "mfu");
	
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
			echo "<script>window.history.back();</script>";
		}
	}
	
	if(isset($_POST['Nothing'])){
		$updateqry = "UPDATE receipt SET status='0' WHERE id='".$_POST['staid']."'";
		if(mysqli_query($con,$updateqry)){
			echo "<script>alert('".$_POST['staid']." Nothing')</script>";
			echo "<script>window.history.back();</script>";
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

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Order List</title>
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
					<a href="#" class="topbar-social-item fa fa-facebook"></a>
					<a href="#" class="topbar-social-item fa fa-instagram"></a>
					<a href="#" class="topbar-social-item fa fa-pinterest-p"></a>
					<a href="#" class="topbar-social-item fa fa-snapchat-ghost"></a>
					<a href="#" class="topbar-social-item fa fa-youtube-play"></a>
				</div>

				<span class="topbar-child1">
					Free shipping for standard order over ฿300
				</span>

				<div class="topbar-child2">
					<span class="topbar-email">
						fashe@example.com
					</span>
				</div>
			</div>

			<div class="wrap_header">
				<!-- Logo -->
				<a href="home.php" class="logo">
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
								<a href="blog.html">Blog</a>
							</li>

							<li>
								<a href="about.html">About</a>
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
											<a href="/mfu/products.php" class="flex-c-m size1s bg1 bo-rad-20 hov1 s-text1 trans-0-4">
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
												<a href="#" class="header-cart-item-name">
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
										<a href="/mfu/mycart.php" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
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

		<!-- Header Mobile -->
		<div class="wrap_header_mobile">
			<!-- Logo moblie -->
			<a href="home.php" class="logo-mobile">
				<img src="/mfu/myimages/mylogo.png">
			</a>

			<!-- Button show menu -->
			<div class="btn-show-menu">
				<!-- Header Icon mobile -->
				<div class="header-icons-mobile">
					<a href="#" class="header-wrapicon1 dis-block">
						<img src="images/icons/icon-header-01.png" class="header-icon1" alt="ICON">
					</a>

					<span class="linedivide2"></span>

					<div class="header-wrapicon2">
						<img src="images/icons/icon-header-02.png" class="header-icon1 js-show-header-dropdown" alt="ICON">
						<span class="header-icons-noti">0</span>

						<!-- Header cart noti -->
						<div class="header-cart header-dropdown">
							<ul class="header-cart-wrapitem">
								<li class="header-cart-item">
									<div class="header-cart-item-img">
										<img src="images/item-cart-01.jpg" alt="IMG">
									</div>

									<div class="header-cart-item-txt">
										<a href="#" class="header-cart-item-name">
											White Shirt With Pleat Detail Back
										</a>

										<span class="header-cart-item-info">
											1 x $19.00
										</span>
									</div>
								</li>

								<li class="header-cart-item">
									<div class="header-cart-item-img">
										<img src="images/item-cart-02.jpg" alt="IMG">
									</div>

									<div class="header-cart-item-txt">
										<a href="#" class="header-cart-item-name">
											Converse All Star Hi Black Canvas
										</a>

										<span class="header-cart-item-info">
											1 x $39.00
										</span>
									</div>
								</li>

								<li class="header-cart-item">
									<div class="header-cart-item-img">
										<img src="images/item-cart-03.jpg" alt="IMG">
									</div>

									<div class="header-cart-item-txt">
										<a href="#" class="header-cart-item-name">
											Nixon Porter Leather Watch In Tan
										</a>

										<span class="header-cart-item-info">
											1 x $17.00
										</span>
									</div>
								</li>
							</ul>

							<div class="header-cart-total">
								Total: $75.00
							</div>

							<div class="header-cart-buttons">
								<div class="header-cart-wrapbtn">
									<!-- Button -->
									<a href="cart.html" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
										View Cart
									</a>
								</div>

								<div class="header-cart-wrapbtn">
									<!-- Button -->
									<a href="#" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
										Check Out
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</div>
			</div>
		</div>

		<!-- Menu Mobile -->
		<div class="wrap-side-menu" >
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
								fashe@example.com
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

					<li class="item-menu-mobile">
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
					</li>
				</ul>
			</nav>
		</div>
	</header>

	<!-- Title Page -->
	<section class="bg-title-page p-t-40 p-b-50 flex-col-c-m" style="background-image: url(myimages/cpattern.png);">
		<h2 class="l-text2 t-center">
			Adenoscence
		</h2>
		<p class="m-text13 t-center">
			Cosmetics Shop
		</p>
	</section>

	<!-- Cart -->
	<center>
	<h1 class="m-text14l p-t-30 p-b-20">Order List</h1>
	<?php if($_SESSION['c']>=8):?>
		<table width="50%" style="max-width:500px;">
		<form method="post">
		<tr>
			<td><button class="flex-c-m size4s bg7 bo-rad-15 hov1 s-text14 trans-0-4" type="submit" name="sortall">View All</button></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td><button class="flex-c-m size4s bg7 bo-rad-15 hov1 s-text14 trans-0-4" type="submit" name="sortnothing">Just Nothing</button></td>
		</tr>
		</form>
		</table><br/>
		<center>
		
		<table><tr><td>
			<div class="search-product pos-relative bo4 of-hidden">
				<form method="get" style="width:80%">
					<input class="s-text7 size6 p-l-23 p-r-50" type="text" name="searchkey" placeholder="Search Receipt ID..."/>
						<button class="flex-c-m size5 ab-r-m color2 color0-hov trans-0-4" type="submit" name="search-receipt">
							<i class="fs-12 fa fa-search" aria-hidden="true"></i>
						</button>
				</td></tr></form>
			</div>
		</table>
		
		<br/>
		</center>
	<?php endif;?>
	<table border=1 width="80%" style="max-width:1000px;">
		<tr>
			<th style="text-align:center;" width="10%">RECEIPT ID</th>
			<?php if($_SESSION['c']>=8):?>
				<th style="text-align:center;">NAME</th>
			<?php endif;?>
			<th style="text-align:center;">ADDRESS</th>
			<th style="text-align:center;" width="10%">PRICE</th>
			<th style="text-align:center;" width="10%">DETAIL</th>
			<?php if($_SESSION['c']>=8):?>
				<th style="text-align:center;">STATUS</th>
				<th style="text-align:center;" width="10%">DELETE</th>
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
					<td align="center"><?php echo $resultusr['name'];?></td>
					<?php $addressinfo = mysqli_query($con, "SELECT * FROM address WHERE id='$aid'"); $resultaddress = mysqli_fetch_array($addressinfo);?>
					<td align="center">
						<?php echo $resultaddress['location'].
									" <br> ต.".$resultaddress['subdistrict'].
									" // อ.".$resultaddress['district'].
									" // จ.".$resultaddress['province'].
									"<br/>Zip:".$resultaddress['zipcode'].
									" // Tel:".$resultaddress['telephone'];
						;?>
					</td>
					<td align="center"><?php echo number_format($total)." ฿";?></td>
					<td align="center" valign="center">
						<button class="bg7 bo-rad-10 hov1 s-text14 trans-0-4" style="width:80%; height:55px;" onclick="hiddenlist('<?php echo $iden;?>')">CLICK</button>
					</td>
					<td valign="center" align="center">
						<table width="80%">
						<form method="post">
							<input type="hidden" name="staid" value="<?php echo $iden;?>">
							<tr>
								<td width="100%">
									<button class="bg7 bo-rad-10 greenhov s-text14 trans-0-4" type="submit" name="Delivered" value="Delivered" style="width:100%">Delivered</button>
								</td>
							</tr>
							<tr>
								<td width="100%">
									<button class="bg7 bo-rad-10 redhov s-text14 trans-0-4" type="submit" name="Nothing" value="Nothing" style="width:100%">Nothing</button>
								</td>
							</tr>
						</form>
						</table>
					</td>
					<td align="center" valign="center">
						<button class="bg7 bo-rad-10 hov1 s-text14 trans-0-4" style="width:80%; height:55px" onclick="if(confirm('This will delete customer order?'))
															{location.href='/mfu/ViewOrder.php?deleteid=<?php echo $iden;?>'}">x</button>
					</td>
				</tr>
				<tr><td>
					<div id="hiddenitem<?php echo $iden;?>" class="mymodal" style="display:none">
					<div class="newcontainer mymodal-content myanimate">
						<p align="right" style="position:relative">
							<span onclick="document.getElementById('hiddenitem<?php echo $iden;?>').style.display='none'" class="myclose trans-0-4" title="Close">&times;</span>
						</p>
						<center><font size="11"><b>Receipt Detail</b></font><br/></center>
						<div class="p-l-15">
							<p align="left">Receipt ID : <?php echo $iden;?> (<?php if($status==0)echo "Nothing";else echo "Delivered";?>)</p>
							<p align="left">Name : <?php echo $resultusr['name'];?> (USER : <?php echo $resultusr['username'];?>)</p>
							<p align="left">Address : <?php echo $resultaddress['location'].
									" // ต.".$resultaddress['subdistrict'].
									" // อ.".$resultaddress['district'].
									" // จ.".$resultaddress['province'].
									" // ".$resultaddress['zipcode']
							;?></p>
							<p align="left">Telephone : <?php echo $resultaddress['telephone'];?></p>
						<center>
							<table width="80%" style="max-width:700px" border=1>
								<tr>
									<th style="text-align:center">Product</th>
									<th style="text-align:center; width:20%">Quantity</th>
									<th style="text-align:center">Total</th>
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
								<br/>
							</table>
							<div class="p-r-70"><p align="right">All Total : <?php echo number_format($total);?> ฿</p></div>
							<br/>
							
							<button onclick="document.getElementById('hiddenitem<?php echo $iden;?>').style.display='none'" 
									class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4">
								Close
							</button>
							
							
						</center>
					</div>
				</div>
				</td></tr>	
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
			<?php $userinfo = mysqli_query($con,"SELECT * FROM login WHERE id='".$uid."'"); $resultusr = mysqli_fetch_array($userinfo);?>
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
					<td align="center" valign="center">
						<div class="p-t-10 p-b-10">
							<button class="bg7 bo-rad-10 hov1 s-text14 trans-0-4" style="width:80%; height:55px;" onclick="hiddenlist('<?php echo $iden;?>')">CLICK</button>
						</div>
					</td>
				</tr>
				<tr><td colspan="4">
					<div id="hiddenitem<?php echo $iden;?>" class="mymodal" style="display:none">
					<div class="newcontainer mymodal-content myanimate">
						<p align="right" style="position:relative">
							<span onclick="document.getElementById('hiddenitem<?php echo $iden;?>').style.display='none'" class="myclose trans-0-4" title="Close">&times;</span>
						</p>
						<center><font size="11"><b>Receipt Detail</b></font><br/></center>
						<div class="p-l-15">
							<p align="left">Receipt ID : <?php echo $iden;?></p>
							<p align="left">Name : <?php echo $resultusr['name'];?></p>
							<p align="left">Address : <?php echo $resultaddress['location'].
									" // ต.".$resultaddress['subdistrict'].
									" // อ.".$resultaddress['district'].
									" // จ.".$resultaddress['province'].
									" // ".$resultaddress['zipcode']
							;?></p>
							<p align="left">Telephone : <?php echo $resultaddress['telephone'];?></p>
						<center>
							<table width="90%" style="max-width:800px" border=1>
								<tr>
									<th style="text-align:center">Product</th>
									<th style="text-align:center; width:20%">Quantity</th>
									<th style="text-align:center">Total</th>
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
								<br/>
							</table>
							<div class="p-r-70"><p align="right">All Total : <?php echo number_format($total);?> ฿</p></div>
							<br/>
							
							<button onclick="document.getElementById('hiddenitem<?php echo $iden;?>').style.display='none'" 
									class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4">
								Close
							</button>
							
						</center>
					</div>
				</div>
				</td></tr>	
			<?php endwhile;?>
		<?php endif;?>
	</table>
	<br/>
	<button class="flex-c-m size1ss bg7 bo-rad-15 hov1 s-text14 trans-0-4" onclick="window.history.back();">Back</button>
	</center>
	<br/>
	
	

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
	<script src="js/main.js"></script>
	
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
