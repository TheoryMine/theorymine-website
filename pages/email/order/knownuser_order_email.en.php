<? include("../pages/email/email_header.php"); ?>

<? $url = "http://theorymine.co.uk"; ?>
<p>
Dear <? print  $val['firstname']; ?> <? print $val['lastname']; ?>,  
<p>CONGRATULATIONS! Your order with <a href="<? print $url ?>">TheoryMine.co.uk</a> was successful! <br/>
Our robot mathematicians are now working on your order for the theorem: </p>
<p align="center">
<? print $val['thm_name']; ?></p>

<p>This is likely to take up to 2 working days (excluding weekends). </p>

<p>
As soon as the theorem is ready, we will send you a confirmation email containing a link to the PDF certificate (a printable copy of the discovery certificate).</p>

<p>You can view the status of your order, access your theorem and certificate information, or change your account details when you
<a href="<? print $url ?>/?go=login">login to your TheoryMine account</a>.

<p>Your PayPal transaction ID for this order is:  <? print $val['paypal_txn_id'];?> <br/>
You will receive a separate email from PayPal confirming your order. 

</p>
<p>
If you require any further information or assistance, please do not hesitate to contact us at <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
or have a look at our <a href="<? print $url ?>/?go=faq">F.A.Q webpage</a>.
</p>
<? include("../pages/email/email_footer.php"); ?>
