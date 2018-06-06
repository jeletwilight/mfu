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
	
	//--------------- Privilege -------------------//
	$allowadd = array(8,9);
	$allowdelete = array(8,9);
	$allowupdate = array(8,9);
	$showaccount = array(0,1,9);
	$addproducttocart = array(1,9);
	//---------------------------------------------//
	
	mysqli_query($con,"SET NAMES UTF8");
	
	//---------------STARTER QUERY-----------------//
	$qry = "SELECT * FROM product ORDER BY released DESC";
    $result = mysqli_query($con,$qry);
	//---------------------------------------------//
	
	//---------------------SORT OPTION-------------------------//
	if(isset($_POST['sort'])){
		$qry = "SELECT * FROM product";
		$option = $_POST['sortid'];
		//$up = $_POST['upper'];
		//$down = $_POST['lower'];
		//$range = " WHERE price>=".$down." AND price<=".$up;
		if($option==0)$ord = " ORDER BY released DESC";
		if($option==1)$ord = " ORDER BY released ASC";
		if($option==2)$ord = " ORDER BY currentprice DESC";
		if($option==3)$ord = " ORDER BY currentprice ASC";
		if($option==4)$ord = " ORDER BY sale DESC";
		$result = mysqli_query($con,$qry.$ord);
	}
	//--------------------------------------------------------//
	
	if(isset($_POST['add_to_cart'])){
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
					echo '<script>alert("This Item Is Already Added");</script>';
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
			echo '<script>alert("Run Out of this product ('.$_POST['hidden_name'].')!")</script>';
			echo '<script>window.location="ShowProduct.php";</script>';
		}
	}
	
	if(isset($_GET['action'])){
		if($_GET['action'] == "delete"){
			foreach($_SESSION["shopping_cart"] as $keys => $values){
				if($values["item_id"] == $_GET['id']){
					unset($_SESSION["shopping_cart"][$keys]);
					echo '<script>alert("Item is Removed");</script>';
					echo '<script>window.location="ShowProduct.php";</script>';
				}
			}
		}
		else if($_GET['action'] == "deleteall"){
			unset($_SESSION["shopping_cart"]);
			echo '<script>alert("Items are Removed");</script>';
			echo '<script>window.location="ShowProduct.php";</script>';
		}
	}
	
	if(isset($_GET['search-product'])){
		$qry = "SELECT * FROM product WHERE name LIKE ";
		$option = $_GET['searchkey'];
		$key = "'%".$option."%'";
		$result = mysqli_query($con,$qry.$key);
	}
	
?>

<html>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>PRODUCT LIST</title>
    <head>
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
    <body>
		<?php print_r($_SESSION); ?>
		<br/>
		<center>
		<h1>PRODUCTS</h1>
		<?php if($_SESSION['login']=="GUEST" || !isset($_SESSION['c'])):?>
		<br/>
		<div class="w-size11">
			<button class="flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" onclick="out();location.href='/mfu/index.php'">Log In</a>
		</div>
		<?php endif;?>
		<table>
		<?php 
			$count = 0;
			$cl = 3;
			while ($row = mysqli_fetch_array($result)):
				$count = $count+1;
				$id = $row['id'];
				$pname = $row['name'];
				$instock = $row['instock'];
				$price = $row['price'];
				$sale = $row['sale'];
				$real = $row['currentprice'];
				$image = $row['imgname'];
				$blob = $row['image'];
				if($count%$cl==1){
					echo "<tr><td>";
				}else{
					echo "<td>";
				}
		?>
			<table border="1">
				<form method="post" action="ShowProduct.php?action=add&id=<?php echo $id;?>">
					<tr>
						<td colspan="3" align="center" width="200" height="250" valign="middle">
							<img src="/mfu/productimages/<?php echo $image; ?>" width="200" height="/width*200" onclick="location.href='/mfu/UpdateForm.php?id=<?php echo $id;?>';">
						</td>
					</tr>
					<tr>
						<th style="text-align:right" width="80" height="40">Name :&nbsp;</th>
						<td width="200" colspan="2"><a href="/mfu/UpdateForm.php?id=<?php echo $id;?>"><?php echo $pname; ?></a></td>
					</tr>
					<tr>
						<th style="text-align:right" height="30">Price :&nbsp;</th>
						<td colspan="2">
							<?php 
								if($sale==0){echo "฿".$price;}
								else{echo "<S style='color:red;'><h>";
										echo "฿".$price;
										echo "</h></S> → ";
										echo "฿".$sale;
									}
							?>
						</td>
					</tr>
				<?php if(in_array($_SESSION['c'],$addproducttocart)):?>
					<tr>
						<input type="hidden" name="hidden_name" value="<?php echo $pname;?>" />
						<input type="hidden" name="hidden_price" value="<?php echo $real;?>" />
						<input type="hidden" name="hidden_instock" value="<?php echo $instock;?>" />
						<th style="text-align:right" height="30px">Quantity :&nbsp;</th>
						<td class="bo2" width="50px"><input type="number" min="1" max="9" name="quan" style="width:50px; height:30px; text-align:right" value="1"></input></td>
						<td align="center"><button type="submit" class="flex-c-m size100 bg7 bo-rad-15 hov1 s-text14 trans-0-4" name="add_to_cart">Add to Cart</button></td>
					</tr>
				</form>
				<?php endif;?>
				<?php if(in_array($_SESSION['c'],$allowdelete)):?>
					<tr>
						<td colspan="3" align="center" height="40px"><a href="/mfu/DeleteBTN.php?id=<?php echo $id;?>" onclick="return confirm('Sure?')">Delete this product</a></td>
					</tr>
				<?php endif;?>
				</table>
				<?php
					if($count%$cl!=0){
						echo "</td><td width='20px'>&nbsp;</td>";
					}else{
						echo "</td></tr><tr height='20px'>&nbsp;</tr>";
					}
				?>
		<?php endwhile; ?>
		</table>
		<?php if(in_array($_SESSION['c'],$allowadd)):?>
		<br/><br/>
		<div class="w-size11">
			<button type="button" class="flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" onclick="location.href='/mfu/AddProductForm.php';">Add</button>
		</div>
		<?php endif;?><br/><br/>
		<h4 class="m-text14 p-b-32">Filters</h4>
		<form method="post">
			<div class="rs2-select2 bo4 w-size12">
			<select class="selection-2" name="sortid" id="sortid">
				<option value=0 selected>Sorting Options</option>
				<option value=0>Released : New to Old</option>
				<option value=1>Released : Old to New</option>
				<option value=2>Price: high to low</option>
				<option value=3>Price: low to high</option>
				<option value=4>Discount</option>
			</select>
			</div><br/><!--
					<div class="search-product pos-relative bo4 of-hidden col-sm-3 col-md-3 col-lg-3">
						<input class="s-text7 size6 p-l-10 p-r-25" type="text" name="lower" />
					</div>
					<div class="search-product pos-relative bo4 of-hidden col-sm-3 col-md-3 col-lg-3">
						<input class="s-text7 size6 p-l-10 p-r-25" type="text" name="upper" />
					</div>-->
		<div class="w-size11">
			<button class="flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" type="submit" name="sort">
				Sort
			</button>
		</div>
		</form><br/>
		<div class="search-product pos-relative bo4 of-hidden col-sm-3 col-md-3 col-lg-3">
			<form method="get">
				<input class="s-text7 size6 p-l-23 p-r-50" type="text" name="searchkey" placeholder="Search Products...">
					<button class="flex-c-m size5 ab-r-m color2 color0-hov trans-0-4" type="submit" name="search-product">
						<i class="fs-12 fa fa-search" aria-hidden="true"></i>
					</button>
			</form>
		</div><br/><br/>
		<?php if(!in_array($_SESSION['c'],$showaccount)):?>
			<?php echo "Account : ".$_SESSION['login'];?>
		</h5><br/><br/>
		<div class="w-size11">
			<button class="flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" onclick="out();location.href='/mfu/index.php'">LOGOUT</a>
		</div><br/>
		<?php endif;?>
		
		<?php if(in_array($_SESSION['c'],$addproducttocart)):?>
		<h1>Shopping Cart</h1><br/><h5>
			<?php if(isset($_SESSION['login'])){
				echo "Account : ".$_SESSION['login'];
			}?></h5><br/>
			<div class="w-size11">
				<button class="flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" onclick="out();location.href='/mfu/index.php'">LOGOUT</a>
			</div><br/>
		<table border="1">
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
				<td><center><?php echo $values["item_price"];?></td>
				<td><center><?php echo number_format($values["item_quantity"] * $values["item_price"]);?></td>
				<td><center><a href="ShowProduct.php?action=delete&id=<?php echo $values['item_id'];?>">Remove</a></td>
			</tr>
			<?php
					$total = $total + ($values["item_quantity"] * $values["item_price"]);
				endforeach;
			?>
			<tr>
				<td colspan="3" align="right">Total :&nbsp;</td>
				<td align="center">฿ <?php echo number_format($total,2)?>
				<td align="center"><a href="ShowProduct.php?action=deleteall">All</a></td>
			</tr>
			<?php endif; ?>
		</table>
			<?php if(isset($_SESSION["shopping_cart"]) && $_SESSION["shopping_cart"] != Array()):;?>
				<br/><center><button class="w-size11 flex-c-m size4 bg7 bo-rad-15 hov1 s-text14 trans-0-4" onclick="alert('Confirmed');location.href='/mfu/ShowProduct.php'">Confirm</a></center>
			<?php endif; ?>
		<?php endif; ?>
		<br/><br/><br/><br/>
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

		$('.block2-btn-addwishlist').each(function(){
			var nameProduct = $(this).parent().parent().parent().find('.block2-name').html();
			$(this).on('click', function(){
				swal(nameProduct, "is added to wishlist !", "success");
			});
		});
	</script>

<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/noui/nouislider.min.js"></script>
	<script type="text/javascript">
		/*[ No ui ]
	    ===========================================================
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
		*/
	</script>
<!--===============================================================================================-->
	<script>
	function out() {
      $.ajax({
           type: "POST",
           url: '/mfu/ClearSession.php',
           /*data:{action:'call_this'},
           success:function(html) {
             alert(html);
           }*/
      });
	}
	</script>


	<script src="js/main.js"></script>
    </body>
</html>



<?php mysqli_close($con); ?>