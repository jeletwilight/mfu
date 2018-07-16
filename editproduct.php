<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
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
	
	$allowupdate = array(8,9);
	$addproducttocart = array(1,9);
	
	mysqli_query($con,"SET NAMES UTF8");
	
	$thisid = $_GET['id'];
	
	$old = mysqli_query($con,"SELECT * FROM product WHERE id='$thisid'");
	$thisrow = mysqli_fetch_array($old);
	if($thisrow != false){
		$oldproductname = $thisrow['name'];
	}
	
	if(isset($_POST['edit'])){
	
		$price = $_POST['price2'];
		$addstock = number_format($_POST['addstock']);
		$sale = $_POST['sale2'];
		$info = $_POST['info2'];
		
		if($sale==0 or $sale=='NULL'){
			$real=$price;
		}else{
			$real=$sale;
		}
		
		
		$sql = "UPDATE product SET instock=instock+'$addstock', price=$price, sale=$sale, currentprice=$real, information='$info' WHERE id=$thisid";
		$qry = mysqli_query($con, $sql);
		
		if($qry){
			echo '<script language="javascript">';
			echo 'alert("Update Sucess!");';
			echo 'window.location.replace("/mfu/editproduct.php?id='.$thisid.'");';
			echo '</script>';
		}
	}
	
?>




<!DOCTYPE html>
<html lang="en">
<head>
	<title>Edit Product</title>
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
					Free shipping for standard order over $100
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

							<li>
								<a href="cart.html">Cart</a>
							</li>

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
					<a href="#">
						<?php 
							if($_SESSION['login']!='GUEST'){
								echo $_SESSION['login']."&nbsp;&nbsp;";
							}else{
								echo "<a href='index.php'>Login&nbsp;&nbsp;</a>";
							}
						?>
					</a>
					<a href="#" class="header-wrapicon1 dis-block">
						<img src="images/icons/icon-header-01.png" class="header-icon1" alt="ICON">
					</a>

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
											Delete All
										</a>
									</div>

									<div class="header-cart-wrapbtn">
										<!-- Button -->
										<a href="#" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
											Check Out
										</a>
									</div>
								</div>
							<?php else:?>
								<a class="header-cart-item-name">
									No item in cart
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
			<a href="index.html" class="logo-mobile">
				<img src="images/icons/logo.png" alt="IMG-LOGO">
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

							<div class="topbar-language rs1-select2">
								<select class="selection-1" name="time">
									<option>USD</option>
									<option>EUR</option>
								</select>
							</div>
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
						<a href="index.html">Home</a>
						<ul class="sub-menu">
							<li><a href="index.html">Homepage V1</a></li>
							<li><a href="home-02.html">Homepage V2</a></li>
							<li><a href="home-03.html">Homepage V3</a></li>
						</ul>
						<i class="arrow-main-menu fa fa-angle-right" aria-hidden="true"></i>
					</li>

					<li class="item-menu-mobile">
						<a href="product.html">Shop</a>
					</li>

					<li class="item-menu-mobile">
						<a href="product.html">Sale</a>
					</li>

					<li class="item-menu-mobile">
						<a href="cart.html">Features</a>
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


	<!-- Product Detail -->
	
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
		
		
	<div class="container bgwhite p-t-35 p-b-80">
		<div class="flex-w flex-sb">
			<div class="w-size13ss p-t-30 respon5">
				<div class="wrap-slick3 flex-sa flex-w">
					<div class="wrap-pic-w">
						<img src="/mfu/productimages/<?php echo $image; ?>" style="width:300px;">
					</div>
				</div>
				<div class="p-t-20">
					<center>Instock : <?php echo $instock;?></center>
				</div>
			</div>

			<div class="w-size14s p-t-30 respon5">
				<h4 class="product-detail-name m-text16 p-b-13">
					<?php echo $name;?>
				</h4>
				<form method="post">
					<a>Price (Standard Price)</a>
					<div class="bo4">
						<input type="text" name="price2" class="p-l-10 size1" value="<?php echo $price;?>"></input>
					</div>
					<a>Sale (Set 0 = Not Sale)</a>
					<div class="bo4">
						<input type="text" name="sale2" class="p-l-10 size1" value="<?php if($sale)echo $sale;else echo 0;?>"></input>
					</div>
					<a>Add To Stock (Negative Number to Decrease)</a>
					<div class="bo4">
						<input type="number" name="addstock" value="0" class="p-l-10 size1" value="0"></input>
					</div>
					<a>Information</a>
					<br/>
						<textarea type="text" name="info2" class="p-l-10" rows="5" style="width:100%"><?php echo $info;?></textarea>
					<div class="p-t-30">
						<center>
							<table style="width:100%">
								<tr>
									<td width="45%"><button type="submit" name="edit" class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4">Submit</button></td>
									<td width="10%"></td>
									<td width="45%">
										<a class="flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4" href='/mfu/products.php'>
											Back
										</a>
									</td>
								</tr>
							</table>
						</center>
					</div>
				</form>
			</div>
		</div>
	</div>
	

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

</body>
</html>