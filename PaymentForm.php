<?php
	$con = mysqli_connect("localhost","root","","mfu");
	
	if($con === false){
		die("ERROR: Could not connect." . mysqli_connect_error());
	}
	
	session_start();
	
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
<html>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<title>Payment and Shipping</title>
	<body>
	<?php print_r($_SESSION); ?>
	<br/>
		<center>
		<h1> Payment & Shipping </h1>
		<br/>
		<h4>
		<?php if(isset($_SESSION['login'])){
			echo "Account : ".$_SESSION['login'];
		}?></h4><br/>
		<h2>Item List</h2>
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
		<br/>
		<form method="post" action="">
			<h2>Shipping Address</h2>
			<table border=1 width="70%">
				<tr>
					<td width="50%" valign="top">
						<input onclick="document.getElementById('addrnew').disabled=true;" type="radio" name="radioaddress" value="1" checked>Default</input>
					</td>
					<td>
						<input onclick="document.getElementById('addrnew').disabled=false;" type="radio" name="radioaddress" value="2">New Address</input>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table>
							<tr>
								<td align="right">Province :</td>
								<td><?php echo $dprovince;?></td>
								<input type="hidden" name="hidden_province" value="<?php echo $dprovince;?>"></input>
							</tr>
							<tr>
								<td align="right">District :</td>
								<td><?php echo $ddistrict;?></td>
								<input type="hidden" name="hidden_district" value="<?php echo $ddistrict;?>"></input>
							</tr>
							<tr>
								<td align="right">Sub-District :</td>
								<td><?php echo $dsubdistrict;?></td>
								<input type="hidden" name="hidden_subdistrict" value="<?php echo $dsubdistrict;?>"></input>
							</tr>
							<tr>
								<td valign="top" align="right">Address :</td>
								<td><?php echo $dlocation;?></td>
								<input type="hidden" name="hidden_location" value="<?php echo $dlocation;?>"></input>
							</tr>
							<tr>
								<td align="right">Zip Code :</td>
								<td><?php echo $dzipcode;?></td>
								<input type="hidden" name="hidden_zip" value="<?php echo $dzipcode;?>"></input>
							</tr>
							<tr>
								<td align="right">Tel. :</td>
								<td><?php echo $dtelephone;?></td>
								<input type="hidden" name="hidden_telephone" value="<?php echo $dtelephone;?>"></input>
							</tr>
						</table>
					</td>
					<td>
						<fieldset style="border:0px" id="addrnew" disabled="true">
							<table>
								<tr>
									<td align="right">Province :</td>
									<td><input type="text" name="newprov" size="40"></input></td>
								</tr>
								<tr>
									<td align="right">District :</td>
									<td><input type="text" name="newdist" size="40"></input></td>
								</tr>
								<tr>
									<td align="right">Sub-District :</td>
									<td><input type="text" name="newsdist" size="40"></input></td>
								</tr>
								<tr>
									<td valign="top" align="right">Address :</td>
									<td><textarea type="text" name="newaddr" rows=5 cols=40></textarea></td>
								</tr>
								<tr>
									<td align="right">Zip Code :</td>
									<td><input type="text" name="newzip" size="5" maxlength="5"></input></td>
								</tr>
								<tr>
									<td align="right">Tel. :</td>
									<td><input type="text" name="newtel" size="10" maxlength="10"></input></td>
								</tr>
								<tr>
									<td colspan=2><input type="checkbox" name="setaddr">Set to Default</td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table><br/>
			<h2>Payment Method</h2>
			<table border=1 width="70%">
				<tr>
					<td width="40%" valign="top">
						<input onclick="pay1();" 
								type="radio" name="radiopay" value="1" checked>Default</input>
					</td>
					<td width="40%">
						<input onclick="pay2();" 
								type="radio" name="radiopay" value="2">New Payment Method</input>
					</td>
					<td>
						<input onclick="pay3();" 
								type="radio" name="radiopay" value="3">PromptPay</input>
					</td>
				</tr>
				<tr>
					<td align="center">
						<fieldset id="payold" style="border:0px">
							<table>
								<tr>
									<td align="right">Card NO. :</td>
									<td><?php echo substr($dcardno,0,4)." - ".substr($dcardno,4,4)." - ".substr($dcardno,8,4)." - ".substr($dcardno,12,4);?></td>
									<input type="hidden" name="hidden_cardno" value="<?php echo $dcardno;?>"></input>
								</tr>
								<tr>
									<td align="right">Holder Name :</td>
									<td><?php echo $dholder;?></td>
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
					<td>
						<fieldset id="paynew" style="border:0px" disabled="true">
							<table>
								<tr>
									<td align="right">Card NO. :</td>
									<td>
										<input size="4" maxlength="4" type="text" name="cdnum1" placeholder="xxxx" required></input> -
										<input size="4" maxlength="4" type="text" name="cdnum2" placeholder="xxxx" required></input> -
										<input size="4" maxlength="4" type="text" name="cdnum3" placeholder="xxxx" required></input> -
										<input size="4" maxlength="4" type="text" name="cdnum4" placeholder="xxxx" required></input>
									</td>
								</tr>
								<tr>
									<td align="right">Holder Name :</td>
									<td><input type="text" name="holder" size="38" placeholder="Full Name"></input></td>
								</tr>
								<!--<tr>
									<td align="right">EXP. Date :</td>
									<td><textarea type="text" name="cardexp" rows=1 cols=5 placeholder="mm/yy"></textarea></td>
								</tr>
								<tr>
									<td valign="top" align="right">PIN :</td>
									<td><textarea type="text" name="cardpin" rows=1 cols=5 placeholder="XXX"></textarea></td>
								</tr>
								<tr>-->
								<td colspan=2><input type="checkbox" name="setcard" id="testing">Set to Default</td>
								</tr>
							</table>
						</fieldset>
					</td>
					<td align="center">
						<fieldset id="prompay" style="border:0px" disabled="true">
							Pay to : 080 000 0000
						</fieldset>
					</td>
				</tr>
			</table><br/>
			
			
			
			
			
			<table>
				<tr>
					<td width="100" align="center"><input type="submit" name="test" value="Test"></input></td>
					<td width="100" align="center"><input type="submit" name="submit" /></td>
					<td width="100" align="center"><button type="button" onclick="window.history.back();">Back</button></td>
					<!------------------------------------  WAITING FOR LINK ^^^^ ----------------------->
				</tr>
			</table><br/><br/>
		</form>
		
		<!--==========================================================================================-->
		<!--==========================================================================================-->
		<script>
		
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
		</script>
	</body>
</html>

<?php mysqli_close($con); ?>