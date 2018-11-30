<?php
require_once("walletaddr.php");

if (isset($_POST['btcAddress'])) {
  $address = $_POST['btcAddress'];

  $shortcode = btc_shortcode($address);
  echo "BTC Shortcode: " . $shortcode;

}

?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>WalletAddr: PHP Library Sample Page</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
</head>
<body>
  <p>Enter your Bitcoin address to generate a Bitcoin shortcode</p>
  <form accept-charset="UTF-8" method="post">
    <input id="btcAddress" name="btcAddress" type="text" style="width:300px"></input>
  	<button>Submit</button>
  </form>
</body>
</html>
