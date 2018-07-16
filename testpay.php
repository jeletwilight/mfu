

<html>
<title>Test Pay</title>
<head><script src="https://cdn.omise.co/card.js" charset="utf-8"></script></head>
<body>
<form name="checkoutForm" method="POST" action="checkout.php">
    <input type="hidden" name="description" value="Product order ฿100.25" />
    <script type="text/javascript" src="https://cdn.omise.co/card.js"
      data-key="pkey_test_5clqnn292j90rkh2zwi"
      data-image="PATH_TO_LOGO_IMAGE"
      data-frame-label="Adenoscene"
      data-button-label="Pay now"
      data-submit-label="Submit"
      data-location="no"
      data-amount="10025"
      data-currency="thb"
      >
    </script>
    <!--the script will render <input type="hidden" name="omiseToken"> for you automatically-->
</form>
</body>
</html>