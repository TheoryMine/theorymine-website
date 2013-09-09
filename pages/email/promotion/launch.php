<? include("pages/email/email_header.php");  ?>
<p>Hello,</p> 
<p>
We are emailing you because you registered with TheoryMine.
</p>
<p><b>NEWS: you can order your theorem now from <a href="http://www.theorymine.co.uk/?go=discover&tname=<? print urlencode($val['email']); ?>">TheoryMine</a>!</b> </p>

<p>Our robot mathematicians are now ready to discover a new theorem for you to name! You can go to <a href="http://www.theorymine.co.uk/?go=discover&tname=<? print urlencode($val['email']);?>">our website</a> now and be one of the first to immortalise your loved ones, teachers, friends, favourite pets or even yourself! </p>

<p>You will receive a 10% discount if you purchase from us before December.  It will take up to two weeks for your theorem to be discovered, so don't leave it too late!</p>

<p>
If you require any further information or assistance, please do not hesitate to contact us at <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
or have a look at our <a href="<? print $url ?>/?go=faq">F.A.Q webpage</a>.
</p>
<? include("pages/email/email_footer.php"); ?>
