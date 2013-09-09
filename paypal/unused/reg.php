<?php
// Register an order has been made for and payed for. 

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

$txn_id = $_REQUEST['txn_id'];


// FIXME: this is only for our paypal ssl workaround. 
$ipnstatus = $_REQUEST['ipnstatus'];
if($ipnstatus == 'VALID') {
  $ipn_status_log = 'valid.';
} else {
  $ipn_status_log = 'unkown.';
}

log_as_point("txid: " . $txn_id, "<code>" . print_r($_REQUEST,true) . "</code><p>", "paypal.request.$ipn_status_log");

// assign posted variables to local variables
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

$fecha = date("Y").'/'.date("m").'/'.date("d");

//check if transaction ID has been processed before
$checkquery = "select txn_id from " . $db_paypal_payment_info . " where txn_id='" . $txn_id . "'";
$row = try_get_row($checkquery);


// check transaction has not already happened; if so ignore it. 
if ($row == null){  
  if($tx_type == "express") { // buying 1 item; not in a cart
    if($item_name == 'Discover and Name a Theorem' 
      and $item_number == 'N1') {
      if($mc_gross == 15) { // correct option and price
        $log_status .= "<p>Success: Discover and Name a Theorem";
        $log_status .= "<p>Success; req= $req";
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

    // log order details 
    $strQuery = "insert into " . $db_paypal_payment_info . "(paymentstatus, buyer_email,firstname, lastname,street,city, state,zipcode, country,mc_gross, mc_fee,itemnumber, itemname,on0,os0,on1,os1, quantity, memo,paymenttype,paymentdate, txn_id,pendingreason,reasoncode,tax,datecreation) values ('".$payment_status."','".$payer_email."','".$first_name. "','".$last_name."','".$address_street."','".$address_city. "','".$address_state."','".$address_zip."','".$address_country. "','".$mc_gross."','".$mc_fee."','".$item_number."','".$item_name. "','".$option_name1."','".$option_selection1."','".$option_name2. "','".$option_selection2."','".$quantity."','".$memo."','". $payment_type."','".$payment_date."','".$txn_id."','". $pending_reason."','".$reason_code."','".$tax."','".$fecha."')";

    $result = sql_query("insert into " . $db_paypal_payment_info . " (paymentstatus,buyer_email,firstname,lastname, street,city,state,zipcode,country,mc_gross,mc_fee,itemnumber, itemname,on0,os0,on1,os1,quantity,memo,paymenttype,paymentdate, txn_id,pendingreason,reasoncode,tax,datecreation) values ('".$payment_status."','".$payer_email."','".$first_name. "','".$last_name."','".$address_street."','".$address_city. "','".$address_state."','".$address_zip."','".$address_country. "','".$mc_gross."','".$mc_fee."','".$item_number."','". $item_name."','".$option_name1."','".$option_selection1."','". $option_name2."','".$option_selection2."','".$quantity."','". $memo."','".$payment_type."','".$payment_date."','".$txn_id. "','".$pending_reason."','".$reason_code."','".$tax."','". $fecha."')");
  } else {
    $log_status .= "<p>Unknown transaction type: $tx_type \nReq: \n$req";
  }
} else {
  // send an email
  $log_status .= "<p>Ignored: DUPLICATED TRANSACTION" . "\nReq: \n$req \n q:$checkquery\n gave back: " . print_r($row, true);
}

log_as_point("txid: $txn_id", // title 
             $log_status, //  point body
             "paypal.status.$ipn_status_log" // log point type
             );
?>
