<?php

	require_once dirname(__FILE__).'\omise-php\lib\Omise.php';
	
	define('OMISE_PUBLIC_KEY', 'pkey_test_5clqnn292j90rkh2zwi');
	define('OMISE_SECRET_KEY', 'skey_test_5clp5guajru30h0j1w4');
	
	echo '<pre>';
		print_r($_POST);
	echo '</pre>';
	
	$charge = OmiseCharge::create(array(
		'amount' => 10025,
		'currency' => 'thb',
		'card' => $_POST['omiseToken'],
	));
	
	if($charge['status'] == 'successful'){
		echo "<script>alert('Successful!');</script>";
		//echo "<script>window.location='products.php'</script>";
	}else{
		echo "<script>alert('Failed!');</script>";
		//echo "<script>window.location='products.php'</script>";
	}
	
?>