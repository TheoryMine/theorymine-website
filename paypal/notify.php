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

$pp_thm_cost = 15;
$pp_item_name = 'Discover and Name a Theorem';
$pp_item_number = 'N1';

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

// log_as_point("txid: " . $txn_id . " : req ", "<code>" . $req . "</code><p>" . print_r($_REQUEST,true), "paypal.");

// log_as_point("notify_called0", "init: " . print_r($_REQUEST, true));
$log_status = "<p>";
$log_status .= "REQ: <code>" . $req . "</code>";

// basic vars we need
$txn_id = sql_str_escape($_REQUEST['txn_id']);
$txn_type = sql_str_escape($_REQUEST['txn_type']);

// log_as_point("precheck: txid: $txn_id;", $log_status, "paypal.precheck.");

// sandbox 
// opening the connection between us an "paypal secure" in order to send them the confirmation back 
//$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
// real paypal
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

//$fp = fopen("debug_invalid.txt");
//$fp = fopen("debug_success.txt", "r");

//  all paypal vars
$paypal_vars = array(
//  Transaction and Notification variables
'business',
'charset',
'custom',
'notify_version',
'parent_txn_id',
'receipt_id',
'receiver_email',
'receiver_id',
'resend',
'residence_country',
'test_ipn',
'txn_id',
'txn_type',
'verify_sign',
// Buyer Information 
'address_country',
'address_city',
'address_country_code',
'address_name',
'address_state',
'address_street',
'address_zip',
'contact_phone',
'first_name',
'last_name',
'payer_business_name',
'payer_email',
'payer_id',
// Payment Information 
'auth_amount',
'auth_exp',
'auth_id',
'auth_status',
'btn_id',
'exchange_rate',
'fraud_management_pending_filters',
'invoice',
'item_name',
'item_number',
'mc_currency',
'mc_fee',
'mc_gross',
'mc_handling',
'mc_shipping',
'memo',
'option_name1',
'option_selection1',
'option_name2',
'option_selection2',
'payer_status',
'payment_date',
'payment_status',
'payment_type',
'pending_reason',
'quantity',
'reason_code',
'remaining_settle',
'settle_amount',
'settle_currency',
'shipping',
'shipping_method',
'tax',
'transaction_entity');

// make everything in paypal_req SQL escaped. 
$paypal_req = array();
foreach($paypal_vars as $v) {
  $paypal_req[$v] = sql_str_escape($_REQUEST[$v]);
}

// note in log what is missing
foreach ($_REQUEST as $key => $value) {
  if($paypal_req[$key] != sql_str_escape($value)) {
    $log_status .= "<br>NOTE: paypal_req missing: $key => $value";
  }
}

// set theorem name, escape and unescaped versions 
$theorem_name = $paypal_req['option_selection1'];
$unescaped_theorem_name = $_REQUEST['option_selection1'];

//language
$language = $paypal_req['option_selection2'];


// TODO: proper error handling: tell the tech-admin of thoerymine that 
// something fishy happened. 

// $fp is the conection to paypal (look above)
if (!$fp) {
  // HTTP ERROR
  $log_status .= "<p>http error: socket file handle is not good.";
  $res_status = "error.paypalhttp.";
} else {
  // post back to PayPal system to validate
  //$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
  //$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  //$header .= "Host: www.sandbox.paypal.com\r\n";

  $header .= "Connection: Close\r\n";
  $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";

  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
  $resp = "";
  fputs ($fp, $header . $req);
  $paypal_res = null;
  // eof = end of file
  while (!feof($fp)) {
    $res = fgets ($fp, 1024); // 1024 means up to 1024 bits
    if (strcmp ($res, "VERIFIED") == 0) {
      $paypal_res = "VERIFIED";
    } else if(strcmp ($res, "INVALID") == 0) {
      $paypal_res = "INVALID";
    } // else ignore: it's header stuff
    $resp .= $res;
  }
  fclose ($fp);
  if($paypal_res == null) {
    $paypal_res = "(undefined)";
  }

  if($paypal_res == "VERIFIED") {
    ?>VERIFIED<?
    // check if transaction ID has been processed before 
    // (may happen on bad server load etc.) Can see what happened on 
    // paypal's logs.
    $checkquery = "select txn_id from " . $db_paypal_payment_info . " where txn_id='" . $txn_id . "'";
    $row = try_get_row($checkquery);
    
    if($row == null){  // check transaction has not already happened. 
      if((subtype($txn_type,'subscr_') or $txn_type == "cart" 
          or $txn_type == "recurring_payment_profile_created" 
          or $txn_type == "new_case" or $txn_type == "adjustment" 
          or $txn_type == "merch_pmt")) {
        // unsupported transatction type
        // FIXME: proper wanring here: email us!
        $log_status .= "<p>STRANGE transation type: '$txn_type'";
        $res_status = "error.txn_type.";
      } else { // paypal don't define exactlty what else this can be, 
               // but all other things are kinds of payments. 

        if($paypal_req['item_name'] == $pp_item_name 
            and $paypal_req['item_number'] == $pp_item_number
            and $paypal_req['mc_gross'] == $pp_thm_cost) 
        { // correct option and price
          $user = try_get_user_by_email($paypal_req['payer_email']); // look up user by email address of register user
          if($user == null) {
            $password = genRandomString(10);// password
            make_new_user($paypal_req['last_name'], $paypal_req['first_name'], 
              $paypal_req['payer_email'], 
                $password
              );
            $user = try_get_user_by_email($paypal_req['payer_email']);
            // the account has not been registred by the user but has been automatically registed by paypal 
            // lock_user($user['id'], 'paypal_creation');
            unlock_user($user['id'], 'paypal_creation');
            //TODO: eiter send email to user with passweord or email to get them to register to check order. 
             $email_vals = array('email' => $paypal_req['payer_email'], 
                'lastname' => $paypal_req['last_name'],
                'firstname' => $paypal_req['first_name'],
                'password'=> $password,
                'uid' => $user['id'],
                'paypal_txn_id' => $txn_id,
                'thm_name' => $unescaped_theorem_name,
                'user_email' => $paypal_req['payer_email']);
             if($language == 'en'){
               $message = email_of_phpfile('../pages/email/order/newuser_order_email.en.php',
                 $email_vals);
             }
             else if($language == 'cn'){
               $message = email_of_phpfile('../pages/email/order/newuser_order_email.cn.php',
                 $email_vals);
             }
             else{
             $message = email_of_phpfile('../pages/email/order/newuser_order_email.php', $email_vals);
             }
             send_email($paypal_req['payer_email'],  'TheoryMine: Order Confirmation', $message);
             
             $message2 = email_of_phpfile('../pages/email/order/us_order_email.php', $email_vals);
             send_email($admin_email, 'TheoryMine : new order', $message2);
             
          }// registered user
          else {
             $email_vals = array('email' => $paypal_req['payer_email'], 
                'lastname' => $paypal_req['last_name'],
                'firstname' => $paypal_req['first_name'],
                'password'=> $password,
                'uid' => $user['id'],
                'paypal_txn_id' => $txn_id,
                'thm_name' => $unescaped_theorem_name,
                'user_email' => $paypal_req['payer_email']);
             if($language == "en"){
               $message = email_of_phpfile('../pages/email/order/knownuser_order_email.en.php', $email_vals);
               }
             else if($language == "cn"){
                $message = email_of_phpfile('../pages/email/order/knownuser_order_email.cn.php', $email_vals);
               }
               else {
                $message = email_of_phpfile('../pages/email/order/knownuser_order_email.php', $email_vals);
               }
               
             send_email($paypal_req['payer_email'], 'TheoryMine : Order Confirmation', $message);
             $message2 = email_of_phpfile('../pages/email/order/us_order_email.php', $email_vals);
             send_email($admin_email, 'TheoryMine : new order', $message2);
          }
     
          $point_id = create_point($user['id'] , 'order.new.', // point type 
              $theorem_name, // name of theorem they requested 
              $txn_id, // paypal transiction id 
              'paypal order creation' // internal log of why this point was created 
              );
          $log_status .= "<p>Success: Discover and Name a Theorem.";
          $res_status = "success.";
        } else {
          // CHECK: probably this is only a problem of mc_gross?
          $log_status .= "<p>Error: Bad product: item_name: '".$paypal_req['item_name']."' (should be $pp_item_name); item_number  : '".$paypal_req['item_number']."' (should be $pp_item_number); mc_gross: '".$paypal_req['mc_gross']."' (should be: $pp_thm_cost);";
          $res_status = "error.item.";
          
          $email_vals = array('paypal' => $paypal_req);
          $m = email_of_phpfile('../pages/email/order/order_error.php', $email_vals);
          send_email($admin_email, 'TheoryMine : order error', $m);
        }
        
        // construct the paypal string to enter into the DB
        // IMPROVE: in theory this could be done off-line, 
        // e.g. at initialisation.
        $paypal_sql_vars_string = '';
        $paypal_sql_values_string = '';
        foreach($paypal_vars as $v) {
          if($paypal_sql_input_vars_string != '') {
            $paypal_sql_input_vars_string .= ',';
            $paypal_sql_values_string .= ",";
          }
          $paypal_sql_input_vars_string .= $v;
          $paypal_sql_values_string .= "'" . $paypal_req[$v] . "'";
        }
        // run the SQL query
        $strQuery = "insert into " .$db_paypal_payment_info. "(".$paypal_sql_input_vars_string.", point_id) values (".$paypal_sql_values_string.",'".$point_id."')";
        $result = sql_query($strQuery);
      }
    } else { // transaction already happened. 
      // send an email
      $log_status .= "<p>Ignored: VERIFIED DUPLICATED TRANSACTION";
      $res_status = "ignored.";
    }
  } else if ($paypal_res == "INVALID") {
    ?>INVALID<?
     // if the IPN POST was 'INVALID'...do this
    // log for manual investigation
    $log_status .= "<p>Error: INVALID IPN";
    $res_status = "error.invalid_ipn.";
  } else {
    print("UNKOWN RESPONSE FROM PAYPAL: " . $resp);
    $res_status = "error.bad_papyal_resp.";
    $log_status .= "<p>Error: UNKOWN RESPONCE FROM PAYPAL: req:<br><pre>$resp</pre>";
  }
}


// create a log of what happened 
log_as_point("txid: $txn_id; txn_type: $txn_type response: $paypal_res", 
  $log_status, 
  "paypal." . $res_status);
?>



