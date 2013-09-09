<?php
// PayPal Notification Script - paypal website sends query here
// This was adapted from PayPal's auto genereated php script (which was buggy!). 

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_REQUEST as $key => $value) {
  $value = urlencode(stripslashes($value));
  $req .= "&$key=$value";
}

// open log file for writing to
$logfp = fopen("log.txt", "a");
if(!$logfp) {
  die("can't open log"); 
}

//$errno2 = 0;
//$errstr2 = '';
$fp2 = fsockopen('www.theorymine.co.uk', 80, $errno2, $errstr2, 30);
if(!$fp2) {
  fclose($logfp);  
  die("can't connect to theorymine: ($errno2) $errstr2"); 
}

// real paypal
//$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
// sandbox 
//$errno = 0;
//$errstr = '';
$fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
if(!$fp) {
  fclose($fp2);  
  fclose($logfp);  
  die("can't connect to paypal: ($errno) $errstr"); 
}


// assign posted variables to local variables
$txn_id = $_REQUEST['txn_id'];
$item_name = $_REQUEST['item_name'];
$business = $_REQUEST['business'];
$item_number = $_REQUEST['item_number'];
$payment_status = $_REQUEST['payment_status'];
$mc_gross = $_REQUEST['mc_gross']; // amount payed in total
$payment_currency = $_REQUEST['mc_currency'];
$receiver_email = $_REQUEST['receiver_email'];
$receiver_id = $_REQUEST['receiver_id'];
$quantity = $_REQUEST['quantity'];
$num_cart_items = $_REQUEST['num_cart_items'];
$payment_date = $_REQUEST['payment_date'];
$first_name = $_REQUEST['first_name'];
$last_name = $_REQUEST['last_name'];
$payment_type = $_REQUEST['payment_type'];
$payment_status = $_REQUEST['payment_status'];
$payment_gross = $_REQUEST['payment_gross'];
$payment_fee = $_REQUEST['payment_fee'];
$settle_amount = $_REQUEST['settle_amount'];
$memo = $_REQUEST['memo'];
$payer_email = $_REQUEST['payer_email'];
$txn_type = $_REQUEST['txn_type'];
$payer_status = $_REQUEST['payer_status'];
$address_street = $_REQUEST['address_street'];
$address_city = $_REQUEST['address_city'];
$address_state = $_REQUEST['address_state'];
$address_zip = $_REQUEST['address_zip'];
$address_country = $_REQUEST['address_country'];
$address_status = $_REQUEST['address_status'];
$item_number = $_REQUEST['item_number'];
$tax = $_REQUEST['tax'];
$option_name1 = $_REQUEST['option_name1'];
$option_selection1 = $_REQUEST['option_selection1'];
$option_name2 = $_REQUEST['option_name2'];
$option_selection2 = $_REQUEST['option_selection2'];
$for_auction = $_REQUEST['for_auction'];
$invoice = $_REQUEST['invoice'];
$custom = $_REQUEST['custom'];
$notify_version = $_REQUEST['notify_version'];
$verify_sign = $_REQUEST['verify_sign'];
$payer_business_name = $_REQUEST['payer_business_name'];
$payer_id =$_REQUEST['payer_id'];
$mc_currency = $_REQUEST['mc_currency'];
$mc_fee = $_REQUEST['mc_fee'];
$exchange_rate = $_REQUEST['exchange_rate'];
$settle_currency  = $_REQUEST['settle_currency'];
$parent_txn_id  = $_REQUEST['parent_txn_id'];
$pending_reason = $_REQUEST['pending_reason'];
$reason_code = $_REQUEST['reason_code'];


// subscription specific vars

$subscr_id = $_REQUEST['subscr_id'];
$subscr_date = $_REQUEST['subscr_date'];
$subscr_effective  = $_REQUEST['subscr_effective'];
$period1 = $_REQUEST['period1'];
$period2 = $_REQUEST['period2'];
$period3 = $_REQUEST['period3'];
$amount1 = $_REQUEST['amount1'];
$amount2 = $_REQUEST['amount2'];
$amount3 = $_REQUEST['amount3'];
$mc_amount1 = $_REQUEST['mc_amount1'];
$mc_amount2 = $_REQUEST['mc_amount2'];
$mc_amount3 = $_REQUEST['mcamount3'];
$recurring = $_REQUEST['recurring'];
$reattempt = $_REQUEST['reattempt'];
$retry_at = $_REQUEST['retry_at'];
$recur_times = $_REQUEST['recur_times'];
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

//auction specific vars
$for_auction = $_REQUEST['for_auction'];
$auction_closing_date  = $_REQUEST['auction_closing_date'];
$auction_multi_item  = $_REQUEST['auction_multi_item'];
$auction_buyer_id  = $_REQUEST['auction_buyer_id'];

if(!$fp2) {
  // HTTP ERROR
  $log_status .= "<p>http error: fwd socket file handle is not good.";
} else if (!$fp) {
  // HTTP ERROR
  $log_status .= "<p>http error: paypal socket file handle is not good.";
} else {
  // HTTP connected to paypal and to theorymine server  

  // post back to PayPal system to validate
  $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  $resp = "";
  fputs ($fp, $header . $req);
  while (!feof($fp)) {
    $res = fgets ($fp, 1024);
    $resp .= $res;
    if (strcmp ($res, "VERIFIED") == 0) {
      $log_status .= "<p>STATUS: VALID IPN: req:<br><pre>$req</pre>";
      $ipnstatus = "VALID";
    } else if (strcmp ($res, "INVALID") == 0) {
      $log_status .= "<p>STATUS: INVALID IPN: req:<br><pre>$req</pre>";
      $ipnstatus = "INVALID";
    }
  }
  fclose($fp);

  $req .= "&ipnstatus=" . $ipnstatus;
  // if successfully verified,
  //$header2 = "POST /vtp2/paypal/reg.php HTTP/1.0\r\n";
  $header2 = "POST /vtp2/paypal/reg.php HTTP/1.1\r\n";
  $header2 .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header2 .= "Host: www.theorymine.co.uk\r\n";
  $header2 .= "Connection: Close\r\n";
  $header2 .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  fwrite($fp2, $header2 . $req);
  fflush($fp2);
  fpassthru($fp2);
  $resp = "";
  while (!feof($fp2)) {
    $res = fgets($fp2, 1024);
    $resp .= $res;
    //if (strcmp ($res, "VERIFIED") == 0) {
    //}
  }
  $log_status .= "RESP: " . $resp; 
  fclose($fp2);
} 

fwrite($logfp, "txid: $txn_id : response: $resp" . $log_status . "\n\n");
fclose($logfp);

// log_as_point("txid: $txn_id : response: $resp", $log_status, "paypal.");
?>
