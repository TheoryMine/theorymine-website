<?
restrict_is_admin();

$here_link = "?go=admin&s=orders";

$act = set_default($_REQUEST['act'], 'search');
$search = set_default($_REQUEST['search'], null);
$offset = set_default(sql_str_escape($_REQUEST['offset']), 0);
$limit = set_default(sql_str_escape($_REQUEST['limit']), 10);
$date = set_default($_REQUEST['date'], null);

// actions that need full info on a single point
if($act == "edit-order" or $act == "show-edit-order"
   or $act == "delete-order" or $act == "attach_thm" or $act == "send_thm") {
  $point_id = set_default($_REQUEST['id'], null);
  $point = get_point($point_id);
}

//
if($act == "attach_thm") {
  if(has_points_related_from($point,'thm.','named.')) {
    $act = "search";
    $search="p.id='$point_id'";
    $limit = 1;
    $date = null;
    new_msg("theorem appears to already have been attached");
  } else {
    $user = get_user_of_point($point);
    $act_body = "named ". sql_str_escape($point['title'])." by ".sql_str_escape($user['firstname'])." ".sql_str_escape($user['lastname'])." ($email)";
    $thm_point = change_some_point($user[id], "point_type = 'thm.unnamed.'",
      array('point_type' => "thm.inprocess.", 'title' => $point['title']),
      'thm.processing1.', $act_body);
    if($thm_point == null){
      new_msg("Action Failed: there are no un-named theorems!");
      $thm_point = null;
      $act="search";
      $search="p.id='$point_id'";
      $limit = 1;
      $date = null;
    } else {
      $rel = array('src_obj_id' => $point_id,
                   'dst_obj_id' => $thm_point['id'],
                   'relation_type' => 'named.');
      $rel_id = create_rel($_SESSION['id'], $rel, $act_body);
      $act_id = edit_point($_SESSION['id'], $point, 'order.new.in_process.',
        sql_str_escape($point['title']), sql_str_escape($point['body']), 'attached theorem');
      $existing_cert = try_get_point_related_from($point,"certificate.","has_certificate.");
      if($existing_cert == null) {
        $cer_title = $point_id.md5($point['title']);
      } else {
        $cer_title = $existing_cert['title'];
      }
      $cer = create_point($user[id], "certificate.", $cer_title," ");
      $rel2 = array('src_obj_id' => $point_id,
                   'dst_obj_id' => $cer,
                   'relation_type' => 'has_certificate.');

      $cer_act_body = "Certificate ". $cer_title." of theorem ".sql_str_escape($point['title']);
      $rel_id2 = create_rel($_SESSION['id'], $rel2, $cer_act_body);

      // this is to show exacyly one order/point after we o the edit
      $act = "search";
      $search="p.id='$point_id'";
      $limit = 1;
      $date = null;
    }
  }
}

if($act == "send_thm") {
  if(has_points_related_from($point,'thm.named.','named.')) {
    $act = "search";
    $search="p.id='$point_id'";
    $limit = 1;
    $date = null;
    new_msg("theorem appears to already have been sent");
  } else {
    $thm_point = get_point_related_from($point,'thm.inprocess.','named.');

    $user = get_user_of_point($point);
    $act_body = "sending theorem: ". sql_str_escape($thm_point['title'])
      . " (" . $thm_point['id'] . ")";

    $thm_point = edit_point($_SESSION['id'], $thm_point, 'thm.named.',
      sql_str_escape($thm_point['title']), sql_str_escape($thm_point['body']), $act_body);
    $act_id = edit_point($_SESSION['id'], $point, 'order.hasthm.',
      sql_str_escape($point['title']), sql_str_escape($point['body']), $act_body);
    $act = "search";
    $search="p.id='$point_id'";
    $limit = 1;
    $date = null;
    $cert =  get_point_related_from($point,"certificate.","has_certificate.");
    $email_vals = array('email' => $user['email'],
         'lastname' => $user['lastname'],
         'firstname' => $user['firstname'],
         'tname' => $point['title'],
         'orderid' => $point['id'],
         //certificate_id'=>($point['id'].md5($point['title'])));
         'certificate_id'=>($cert['title']));

    $selected_radio = $_POST['lang_email'];
    $emailfile = 'pages/email/order/user_fulfilled_email.'.$selected_radio.'.php';
    $message = email_of_phpfile( $emailfile, $email_vals);
    send_email( $user['email'], 'TheoryMine : Discovered ' .  $point['title'], $message);

    $message2 = email_of_phpfile('pages/email/order/us_fulfilled_email.php', $email_vals);
    send_email($admin_email, 'TheoryMine : ORDER FULFILLED ', $message2);
  }
}


if($act == "edit-order" or $act == "make-new-order"){
  $user_id = set_default(sql_str_escape($_REQUEST['user_id']), null);
  $abody = set_default(sql_str_escape($_REQUEST['abody']), null);

  $order_name = set_default(sql_str_escape($_REQUEST['order_name']), null);
  $order_body = set_default(sql_str_escape($_REQUEST['order_body']), null);
  $order_status = set_default(sql_str_escape($_REQUEST['order_status']), null);

  if($act == "edit-order"){
    // edit the theorem
    edit_point($user_id, $point, $order_status, $order_name, $order_body, $abody);
    $act='search';
    $search="p.id='$point_id'";
    $limit = 1;
    $date = null;
  }

  if($act == "make-new-order"){
    $point_id = create_point($user_id, $order_status, $order_name, $order_body, $abody);
    $point = get_point($point_id);
    $act='search';
    $search="p.id='$point_id'";
    $limit = 1;
    $date = null;
  }
}

if($act == "delete-order"){
  if($point != null){
    if(delete_point($_SESSION['id'], $point) != null) {
        ?><h3 class="warning">Deleted order:</h3><?
        $act='search';
        $search=null;
        $date = null;
    }
    ?>
    <div class="red-block">
    (<? print $point['id']; ?>) <? print $point['title']; ?><br>
    order body: <? print $point['body']; ?><br>
    order status: <? print $point['point_type']; ?><br>
    history_id: <? print $point['history_id']; ?><br>
    action_id: <? print $point['action_id']; ?><br>
    time stamp: <? print $point['time_stamp']; ?>
    </div>
    <?
  } else {
    ?><p>
    <span class="warning"><? print $act; ?> (<? print $point_id; ?>) failed.</span>
    </p><?
    $act='search';
    $search=null;
    $date = null;
  }
}
?>

<? // generic message display
if($msgs != null){
  ?><div class="warning_msgs">
  <? if($here_link != null) { ?>
    <div class="msg-tools"><a href="<? print($here_link); ?>">clear messages</a></div>
    <?
  }
  $firstmsg = true;
  foreach($msgs as $m) {
    if($firstmsg == true) { ?><div class="msg1"><? $firstmsg = false; }
    else{ ?><div class="msg"><? }
    print($m);
    ?></div><?
  }
  ?></div><?
}
?>

<h1>Orders</h1>
  <form action="?go=admin&s=orders" method="post">
  <input type="hidden" name="act" value="search" size="70">
  <b>Search:</b> <input type="text" name="search" value="<? print $search; ?>" size="70"><br>
  Offset:
  <input type="text" name="offset" value="<? print $offset; ?>" size="10">; Limit: <input type="text" name="limit" value="<? print $limit; ?>" size="10">
  Creation Date: <input type="text" name="date" value="<? print $date; ?>" size="20">
  <input class="greenbutton" type="submit" value="Search!"> &nbsp; <a class="greenbutton" href="?go=admin&s=orders">Show All</a><br>
  SQL added to WHERE e.g. <code>p.id = '3'</code> for finding points with id of 3, <code>p.title >= 'pants'</code> for finding all points where the title contains the substring 'pants'.
  </form>

<?
if($act == 'search') {
  if ($date != null and trim($date) != ""){
    if ($search != null and trim($search) != "")
    {
      $search = $search . "AND ca.time_stamp LIKE " . "'".$date."%'";
    }
    else { $search = " ca.time_stamp LIKE " . "'".$date."%'";
    }
  }
  $res = get_from_points_and_actions_and_user($search, "AND p.point_type LIKE 'order.%'", $offset, $limit);
  $rows = $res['rows'];

  if($rows != null) { ?>
    <h3>Found <? print($res['rowcount']); ?> Points:</h3>
    <div class="simple-border">
    <?
    $toggle = true;
    $fst = true;
    foreach($rows as $point) {
      $toggle = !$toggle;
      if($fst){ $fst = false;
        ?><div class="simple-list0"><?
      } else if($toggle){
        ?><div class="simple-list1"><?
      } else {
        ?><div class="simple-list2"><?
      } ?>
      <div class="edit-btns-right"><a href="?go=admin&s=orders&act=show-edit-order&id=<? print $point['id']; ?>">edit</a> | <a href="?go=admin&s=orders&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>&limit=1">show/process</a></div>


      id: <? print $point['id']; ?>; Order to name a theorem: "<? print $point['title']; ?>"<br>
      date: <?print $point['time_stamp']?> order status: <? print $point['point_type']; ?>
      <?
      if($point['point_type'] == 'order.new.') {
        ?>[ New order ]
        <?
      } else if(subtype($point['point_type'],'order.hasthm.')) {
         ?>[ Has attached theorem ]
        <?
        $cert =  get_point_related_from($point,'certificate.','has_certificate.');
        if($cert == null) {
          print "something is wrong with certid";
        }
        else{
          $thm_file_dir="certificates/".($cert['title'])."/thm.jpg";
          $thy_file_dir="certificates/".($cert['title'])."/thy.jpg";
          $cert_file_dir="certificates/".($cert['title']). "/certificate.pdf";
          $cert_image_dir="certificates/".($cert['title']). "/certificate_image.jpg";
           $brouch_file_dir="certificates/".($cert['title']). "/brouchure.pdf";
          ?>
            <font style="color:#CC0000;">
            <?
          if (!file_exists( $thm_file_dir)){
            print (" | MISSING: thm image | ");
          }
            if (!file_exists( $thy_file_dir)){
              print (" | MISSING: thy image | ");
          }
            if (!file_exists( $cert_file_dir)){
              print (" | MISSING: certificate | ");
          }
              if (!file_exists( $cert_image_dir)){
              print (" | MISSING: cert image | ");
          }
            if (!file_exists( $brouch_file_dir)){
              print (" | MISSING: brouchure | ");
          }?>
          </font>
          <?
        }


      } else if(subtype($point['point_type'],'order.new.in_process.')) {
      ?>[ In Process ]
      <?
      } else if(subtype($point['point_type'],'order.hascert.')) {
        ?>[ Has attached theorem and certificate ]<?
      }
      ?>
      <br>
      <?
      if($res['rowcount'] == 1 and $limit == 1) {
        $user = get_user_of_point($point);

        if($point['point_type'] == 'order.new.') {
        ?>
        <form action="?go=admin&s=orders" method="post">
        <input type="hidden" name="act" value="attach_thm">
        <input type="hidden" name="id" value="<? print($point['id']); ?>">
        <input class="greenbutton" type="submit" value="Attach a Theorem">
        </form>
        <?
      } else if(subtype($point['point_type'],'order.new.in_process.')) {
        /* FIXME: 2 rpoblems:
        1. $point['id'].md5($point['title']) is a key that can easily be spoofed. Better to use: $point['id'].md5($point['id'].$point['title'])
        2. when a certificate is attached, we should lookup the certificate point, and use it's title. We should not recompute the hash, as recomputin the hash means we can't change the way the hash is computed.
        */
        $cert =  get_point_related_from($point,"certificate.","has_certificate.");

        ?>
        <p><b>Order is in progress.</b></p>
        <p>To generate the certificate from the
        the <a href="https://github.com/TheoryMine/theorymine-docker">theorymine
        docker</a> directory run: </p>
        <p><pre>
        sh generate_certificate.sh <? print ($cert['title']); ?>
        </pre></p>
        <p>This will put the generated certificate files in the local <pre>docker_shared_dir</pre></p>
        <p>
          <a href="?go=admin&s=certificate3&pid=<?
            print ($cert['title']);
            ?>">Upload files</a>
            <br>
          <a href="/certificates/<? print ($cert['title']); ?>/certificate.pdf">
            View "certificate.pdf"</a>
        </p>
        <form action="?go=admin&s=orders" method="post">

        <input type="radio" name="lang_email" value="en"  checked /> English<br />
        <input type="radio" name="lang_email" value="cn" /> Chinese<br />
        <input type="radio" name="lang_email" value="sp" /> Spanish<br />

        <input type="hidden" name="act" value="send_thm">
        <input type="hidden" name="id" value="<? print($point['id']); ?>">
        <input class="greenbutton" type="submit" value="Send Theorem">
        </form>
        <?
      } else if(subtype($point['point_type'],'order.hasthm.')) {
        $cert =  get_point_related_from($point,"certificate.","has_certificate.");
        $thm=  get_point_related_from($point,"thm.named.","named.");
        ?><p><b>Order is fully processed</b>.</p>
        <p>
          <a href="?go=admin&s=certificate3&pid=<?
            print ($cert['title']);
            ?>">Upload files</a>
            <br>
          <a href="/certificates/<? print ($cert['title']); ?>/certificate.pdf">
            View "certificate.pdf"</a>
        </p>
        <br/><?
      }
      ?>
      <p>
      Order body (paypal txn id): <span class="simple-block"><? print $point['body']; ?></span><br>
        ordered by user (<? print $user['id']; ?>)
        <? print $user['firstname']; ?> <? print $user['lastname']; ?>,
        <? print $user['email']; ?><br>
        history_id: <? print $point['history_id']; ?><br>
        prev_id: <? print $point['prev_id']; ?><br>
        action_id: <? print $point['action_id']; ?><br>
        time_stamp: <? print $point['time_stamp']; ?><br>
        creation_time: <? print $point['creation_date']; ?><br>
        action_type: <? print $point['action_type']; ?>; action_timestamp: <? print $point['a_time_stamp']; ?>;
        action_body: <? print $point['action_body']; ?>; ipaddr: <? print $point['ipaddr']; ?><br>
        user_id: <? print $point['user_id']; ?>; firstname: <? print $point['firstname']; ?>; lastname: <? print $point['lastname']; ?>; email: <? print $point['email']; ?>;
        last_act_time: <? print $point['last_act_time']; ?>;
        last_act_kind: <? print $point['last_act_kind']; ?><br>
        <?
        $row = try_get_row("SELECT * FROM $db_paypal_payment_info WHERE txn_id = '" . sql_str_escape($point['body']) . "'");
        if($row == null) {
          ?><div class="warning">This order has no associated paypal payement record.</div><?
        } else {
          ?><div class="simple-border"><?
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
          foreach($paypal_vars as $k) {
            print($k . ": " . $row[$k] . "<br />");
          }
        }
        ?>
        <?
      }
      //print_r($point);
      ?>
      </div>
      <?
      if($res['rowcount'] == 1 and $limit == 1) {
        print_related_points($point);
      }
    }
    ?>
    </div>
    <?
    // rows != null
  } else {
    ?><p><span class="warning">No entries</span></p><?
  }
}


if($act == "show-edit-order"){
  if($point['user_id'] == null or $point['user_id'] == ""){
    $point['user_id'] = $_SESSION['id'];
  }
  ?>
  <p>

  <div class="simple-block">
  <h3> Edit Order </h3>

  <form action="?go=admin&s=orders" method="post">
  <input type="hidden" name="act" value="edit-order">
  <input type="hidden" name="id" value="<? print($point['id']); ?>">

  <table border="0">
  <tr><td align="right" valign="top">
  Order Name:
  </td><td>
  <input type="text" name="order_name" size="60" value="<? print(htmlentities($point['title'])); ?>">
  </td>
  </tr><tr>
    <td valign="top" align="right">Order body:</td>
    <td valign="top"><input type="text" name="order_body" size="60" value="<? print(htmlentities($point['body'])); ?>"></td>
  </tr><tr>
    <td align="right">Order status:</td>
    <td valign="top"><select name="order_status">
    <option value="order.new." <? if($point['point_type'] == 'order.new.'){ print("selected"); } ?>>New order</option>
     <option value="order.new.in_process." <? if($point['point_type'] == 'order.new.in_process.'){ print("selected"); } ?>>in process</option>
      <option value="order.hasthm." <? if($point['point_type'] == 'order.hasthm.'){ print("selected"); } ?>>Has attached theorem</option>
    </select></td>
  </tr><tr>
  <td colspan="2"></td>
  </tr><tr>
  <td colspan="2"><br>This action:</td>
  </tr><tr>
    <td align="right">user_id:</td>
    <td><input type="text" name="user_id" size="20" value="<? print(htmlentities($point['user_id'])); ?>"></td>
  </tr><tr>
    <td align="right">action_description:</td>
    <td><input type="text" name="abody" size="20" value="<? print(htmlentities($abody)); ?>"></td>
  </tr>
  <tr><td colspan="2" align="center"><br>
  <input class="greenbutton" type="submit" value="Save changes"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=orders&act=search&search=<? print urlencode("p.id='" . $point['id'] . "'"); ?>">Cancel</a></td></tr>
  </table>
  </form>

  <h4>Other actions</h4>
  <!-- <br><br>
  To update the last-change timestamp to now:<br>
  <a class="greenbutton" href="?go=admin&s=orders&act=touch&obj_id=<? print($point['id']); ?>" method="post">Update time</a>
  <br><br> -->
  To <span class="warning">delete</span> the order:
  <a class="redbutton" href="?go=admin&s=orders&act=delete-order&id=<? print($point['id']); ?>" method="post">Delete order</a>

  </div>
  <?
} else {
  $point = array();
}

if($act == "enter-new-order"){
  if($user_id == null or $user_id == ""){ $user_id = $_SESSION['id']; }
  ?>
  <p>
  <div class="simple-block">
  <h3> New Order Details </h3>
  <form action="?go=admin&s=orders" method="post">
  <input type="hidden" name="act" value="make-new-order">
  <table border="0">
  <tr><td align="right" valign="top">
  Order Name:
  </td><td>
  <input type="text" name="order_name" size="60" value="<? print(htmlentities($order_name)); ?>">
  </td>
  </tr><tr>
    <td valign="top" align="right">Order body (paypal txn_id):</td>
    <td valign="top"><input type="text" name="order_body" size="60" value="<? print(htmlentities($order_body)); ?>"></td>
  </tr><tr>
    <td align="right">Order status:</td>
    <td><select name="order_status">
    <option value="order.new." <? if($order_status == 'order.new.'){ print("selected"); } ?>>new order</option>
   <option value="order.new.in_process." <? if($order_status == 'order.new.in_process.'){ print("selected"); } ?>>in process</option>
       <option value="order.hasthm." <? if($order_status == 'order.hasthm.'){ print("selected"); } ?>>has attached theorem</option>
    </select></td>
  </tr><tr>
  <td colspan="2"></td>
  </tr><tr>
  <td colspan="2"><br>This action:</td>
  </tr><tr>
    <td align="right">user_id:</td>
    <td><input type="text" name="user_id" size="20" value="<? print(htmlentities($user_id)); ?>"></td>
  </tr><tr>
    <td align="right">action_description:</td>
    <td><input type="text" name="abody" size="20" value="<? print(htmlentities($abody)); ?>"></td>
  </tr><tr>
    <td colspan="2" align="center">
    <br>
    <input class="greenbutton" type="submit" value="Make new order"> &nbsp;&nbsp; <a class="redbutton" href="?go=admin&s=orders">Cancel</a></td>
  </tr>
  </table>
  </form>
  </div>
  <?
} else {
  ?><p><a href="?go=admin&s=orders&act=enter-new-order">Make a new order</a></p>
<?
}
?>
