<?php
// PayPal Notification Script - paypal website sends query here
// This was adapted from PayPal's auto genereated php script (which was buggy!). 

//include basic libs
include "../utils/common.php";
include "../utils/prefs.php"; // sets the db_table names 
// sql libs
include "../utils/sql_lib.php";
include "../utils/sql_actions.php";
include "../utils/sql_user.php";
include "../utils/sql_points.php";
// include "debug_post.php"; // sets the db_table names 

$prefs_filename = '../prefs/prefs.php'; // location of global prefs
$sqlprefs_filename = 'sql_prefs.php'; // sql prefs filename

// if prefs file exists, proceed as normal
if(! load_global_prefs($prefs_filename)) {
  die("could not load global prefs:" . $prefs_filename);
} elseif(! load_sql_prefs("../" . $sqlprefs_fullfilename)) {
  die("could not load sql prefs:" . $sqlprefs_fullfilename );
} elseif(! sql_has_tables($db_tables)) {
  die("sql does not have the right tables: " . $db_tables);
} else { 
  // working ok
}

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_REQUEST as $key => $value) {
  $value = urlencode(stripslashes($value));
  $req .= "&$key=$value";
}

$txn_id = sql_str_escape($_REQUEST['txn_id']);

// log_as_point("txid: " . $txn_id . " : req ", "<code>" . $req . "</code><p>" . print_r($_REQUEST,true), "paypal.");

// log_as_point("notify_called0", "init: " . print_r($_REQUEST, true));
$log_status = "<p>";
$log_status .= "REQ: <code>" . $req . "</code>";

// sandbox 
$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
// real paypal
//$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

//$fp = fopen("debug_invalid.txt");
//$fp = fopen("debug_success.txt", "r");


// assign posted variables to local variables
$item_name = sql_str_escape($_REQUEST['item_name']);
$business = sql_str_escape($_REQUEST['business']);
$item_number = sql_str_escape($_REQUEST['item_number']);
$payment_status = sql_str_escape($_REQUEST['payment_status']);
$mc_gross = sql_str_escape($_REQUEST['mc_gross']); // amount payed in total
$payment_currency = sql_str_escape($_REQUEST['mc_currency'];
$receiver_email = sql_str_escape($_REQUEST['receiver_email'];
$receiver_id = sql_str_escape($_REQUEST['receiver_id'];
$quantity = sql_str_escape($_REQUEST['quantity'];
$num_cart_items = sql_str_escape($_REQUEST['num_cart_items'];
$payment_date = sql_str_escape($_REQUEST['payment_date'];
$first_name = sql_str_escape($_REQUEST['first_name']);
$last_name = sql_str_escape($_REQUEST['last_name']);
$payment_type = sql_str_escape($_REQUEST['payment_type']);
$payment_status = sql_str_escape($_REQUEST['payment_status']);
$payment_gross = sql_str_escape($_REQUEST['payment_gross']);
$payment_fee = sql_str_escape($_REQUEST['payment_fee']);
$settle_amount = sql_str_escape($_REQUEST['settle_amount']);
$memo = sql_str_escape($_REQUEST['memo']);
$payer_email = sql_str_escape($_REQUEST['payer_email']);
$txn_type = sql_str_escape($_REQUEST['txn_type']);
$payer_status = sql_str_escape($_REQUEST['payer_status']);
$address_street = sql_str_escape($_REQUEST['address_street']);
$address_city = sql_str_escape($_REQUEST['address_city']);
$address_state = sql_str_escape($_REQUEST['address_state']);
$address_zip = sql_str_escape($_REQUEST['address_zip']);
$address_country = sql_str_escape($_REQUEST['address_country']);
$address_status = sql_str_escape($_REQUEST['address_status']);
$item_number = sql_str_escape($_REQUEST['item_number']);
$tax = sql_str_escape($_REQUEST['tax']);
$option_name1 = sql_str_escape($_REQUEST['option_name1']);
$option_selection1 = sql_str_escape($_REQUEST['option_selection1']);
$option_name2 = sql_str_escape($_REQUEST['option_name2']);
$option_selection2 = sql_str_escape($_REQUEST['option_selection2']);
$for_auction = sql_str_escape($_REQUEST['for_auction']);
//$invoice = $_REQUEST['invoice'];
$custom = sql_str_escape($_REQUEST['custom']);
$notify_version = sql_str_escape($_REQUEST['notify_version']);
$verify_sign = sql_str_escape($_REQUEST['verify_sign']);
$payer_business_name = sql_str_escape($_REQUEST['payer_business_name']);
$payer_id = sql_str_escape($_REQUEST['payer_id']);
$mc_currency = sql_str_escape($_REQUEST['mc_currency']);
$mc_fee = sql_str_escape($_REQUEST['mc_fee']);
$exchange_rate = sql_str_escape($_REQUEST['exchange_rate']);
$settle_currency = sql_str_escape($_REQUEST['settle_currency']);
$parent_txn_id  = sql_str_escape($_REQUEST['parent_txn_id']);
$pending_reason = sql_str_escape($_REQUEST['pending_reason']);
$reason_code = sql_str_escape($_REQUEST['reason_code']);


// subscription specific vars

$subscr_id = sql_str_escape($_REQUEST['subscr_id']);
$subscr_date = sql_str_escape($_REQUEST['subscr_date']);
$subscr_effective  = sql_str_escape($_REQUEST['subscr_effective']);
$period1 = sql_str_escape($_REQUEST['period1']);
$period2 = sql_str_escape($_REQUEST['period2']);
$period3 = sql_str_escape($_REQUEST['period3']);
$amount1 = sql_str_escape($_REQUEST['amount1']);
$amount2 = sql_str_escape($_REQUEST['amount2']);
$amount3 = sql_str_escape($_REQUEST['amount3']);
$mc_amount1 = sql_str_escape($_REQUEST['mc_amount1']);
$mc_amount2 = sql_str_escape($_REQUEST['mc_amount2']);
$mc_amount3 = sql_str_escape($_REQUEST['mcamount3']);
$recurring = sql_str_escape($_REQUEST['recurring']);
$reattempt = sql_str_escape($_REQUEST['reattempt']);
$retry_at = sql_str_escape($_REQUEST['retry_at']);
$recur_times = sql_str_escape($_REQUEST['recur_times']);
$username = sql_str_escape($_REQUEST['username']);
$password = sql_str_escape($_REQUEST['password']);

//auction specific vars
$for_auction = sql_str_escape($_REQUEST['for_auction']);
$auction_closing_date = sql_str_escape($_REQUEST['auction_closing_date']);
$auction_multi_item  = sql_str_escape($_REQUEST['auction_multi_item']);
$auction_buyer_id  = sql_str_escape($_REQUEST['auction_buyer_id']);


if (!$fp) {
  // HTTP ERROR
  $log_status .= "<p>http error: socket file handle is not good.";
} else {
  // post back to PayPal system to validate
  $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Host: www.sandbox.paypal.com\r\n";
  $header .= "Connection: Close\r\n";
  //$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
  //$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  $resp = "";
  fputs ($fp, $header . $req);
  while (!feof($fp)) {
    $res = fgets ($fp, 1024);

    $resp .= $res;
    if (strcmp ($res, "VERIFIED") == 0) {
      ?>VERIFIED<?
      $fecha = date("Y").date("m").date("d");

      //check if transaction ID has been processed before
      $checkquery = "select txn_id from " . $db_paypal_payment_info . " where txn_id='" . $txn_id . "'";
      $row = try_get_row($checkquery);
      
      if ($row == null){  // check transaction has not already happened. 
        if ($txn_type == "cart"){
          // we are not using carts at the moment; log error: 
          $log_status .= "<p>error: unexpected cart.";
          
          $strQuery = "insert into " . $db_paypal_payment_info . "(paymentstatus,buyer_email,firstname,lastname, street,city,state,zipcode,country,mc_gross,mc_fee,memo,paymenttype, paymentdate,txn_id,pendingreason,reasoncode,tax,datecreation) values ('".$payment_status."','".$payer_email."','".$first_name."','" . $last_name."','".$address_street."','".$address_city."','". $address_state."','".$address_zip."','".$address_country."','". $mc_gross."','".$mc_fee."','".$memo."','".$payment_type."','". $payment_date."','".$txn_id."','".$pending_reason."','".$reason_code ."','".$tax."','".$fecha."')";
          $result = sql_query($strQuery);

          for ($i = 1; $i <= $num_cart_items; $i++) {
            $itemname = "item_name".$i;
            $itemnumber = "item_number".$i;
            $on0 = "option_name1_".$i;
            $os0 = "option_selection1_".$i;
            $on1 = "option_name2_".$i;
            $os1 = "option_selection2_".$i;
            $quantity = "quantity".$i;
            
            $struery = "insert into " . $db_paypal_cart_info . "(txn_id,itemnumber,itemname,on0,os0, on1,os1, quantity, invoice,custom) values ('".$txn_id."','".$_REQUEST[$itemnumber]."','".$_REQUEST[$itemname]."','" .$_REQUEST[$on0]."','".$_REQUEST[$os0]."','".$_REQUEST[$on1]."','". $_REQUEST[$os1]."','".$_REQUEST[$quantity]."','".$invoice."','".$custom. "')";
            $result = sql_query($struery);
          }
        } else { // buying 1 item; not in a cart
          if($txn_type != "web_accept") {
            $log_status .= "<p>STRANGE transation type: '$txn_type'";
          }
          if($item_name == 'Discover and Name a Theorem' 
            and $item_number == 'N1') {
            if($mc_gross == 15) { // correct option and price
              $log_status .= "<p>Success: Discover and Name a Theorem";
              
              $point_id = create_point($user_id, 'order.new.', $option_selection1, $txn_id, '');
              //$user_id = sql_str_escape($custom);
              // *** FIXME ***
              //complete_naming_of_thm($user_id, $txn_id, 1);
            } else {
              // CHECK: probably this is only a problem of mc_gross?
              $log_status .= "<p>Error: Bad option_name1 or mc_gross: '$option_selection1' and '$mc_gross'";
            }
          } else {
            $log_status .= "<p>Error: Bad item_name/item_number: '$item_name' and '$item_number'";
          }

          $strQuery = "insert into " . $db_paypal_payment_info . "(paymentstatus, buyer_email,firstname, lastname,street,city, state,zipcode, country,mc_gross, mc_fee,itemnumber, itemname,on0,os0,on1,os1, quantity, memo,paymenttype,paymentdate, txn_id,pendingreason,reasoncode,tax,datecreation,custom,invoice) values ('".$payment_status."','".$payer_email."','".$first_name. "','".$last_name."','".$address_street."','".$address_city. "','".$address_state."','".$address_zip."','".$address_country. "','".$mc_gross."','".$mc_fee."','".$item_number."','".$item_name. "','".$option_name1."','".$option_selection1."','".$option_name2. "','".$option_selection2."','".$quantity."','".$memo."','". $payment_type."','".$payment_date."','".$txn_id."','". $pending_reason."','".$reason_code."','".$tax."','".$fecha."')";
          $result = sql_query($strQuery);
        }

        $log_status .= "<p>Success: VERIFIED IPN: $res\n $req";
      } else {
        // send an email
        $log_status .= "<p>Ignored: VERIFIED DUPLICATED TRANSACTION" . "res:$res\n req:$req \n q:$checkquery\n gave back: " . print_r($row, true);
      }
      
      //subscription handling branch
      if ( $txn_type == "subscr_signup"  ||  $txn_type == "subscr_payment"  ) {
        // insert subscriber payment info into paypal_payment_info table
        $strQuery = "insert into " . $db_paypal_payment_info . " (paymentstatus,buyer_email,firstname, lastname,street,city,state,zipcode,country,mc_gross,mc_fee,memo, paymenttype,paymentdate,txn_id,pendingreason,reasoncode,tax, datecreation) values ('".$payment_status."','".$payer_email."','".$first_name."','". $last_name."','".$address_street."','".$address_city."','". $address_state."','".$address_zip."','".$address_country."','". $mc_gross."','".$mc_fee."','".$memo."','".$payment_type."','". $payment_date."','".$txn_id."','".$pending_reason."','". $reason_code."','".$tax."','".$fecha."')";
        $result = sql_query($strQuery);
        
        // insert subscriber info into paypal_subscription_info table
        $strQuery2 = "insert into " . $db_paypal_subscription_info . "(subscr_id , sub_event, subscr_date ,subscr_effective,period1,period2, period3, amount1 ,amount2 ,amount3,  mc_amount1,  mc_amount2,  mc_amount3, recurring, reattempt,retry_at, recur_times, username ,password, payment_txn_id, subscriber_emailaddress, datecreation) values ('".$subscr_id."', '".$txn_type."','".$subscr_date."','".$subscr_effective."','". $period1."','".$period2."','".$period3."','".$amount1."','". $amount2."','".$amount3."','".$mc_amount1."','".$mc_amount2. "','".$mc_amount3."','".$recurring."','".$reattempt."','". $retry_at."','".$recur_times."','".$username."','".$password. "', '".$txn_id."','".$payer_email."','".$fecha."')";
        $result = sql_query($strQuery2);
        
        $log_status .= "<p>Success: VERIFIED IPN: res\n $req\n $strQuery\n $struery\n  $strQuery2";
      }
    } else if (strcmp ($res, "INVALID") == 0) {
      ?>INVALID<?
       // if the IPN POST was 'INVALID'...do this
      // log for manual investigation
      $log_status .= "<p>Error: INVALID IPN: req:<br><pre>$req</pre>";
    }
  }
  fclose ($fp);
}
log_as_point("txid: $txn_id : response: $resp", $log_status, "paypal.");
?>
