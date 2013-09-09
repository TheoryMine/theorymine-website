<? include("pages/email/email_header.php"); 
 $url = "http://theorymine.co.uk/"; ?>
<p>Hello,</p> 
<p>
We are emailing you to let you know that you can now purchase T-shirts, mouse-pads and mugs personalized with your TheoryMine theorem!
</p>
<p>

</p>
<p>
To do so <a href="<?print $url?>?go=login">log into</a> your TheoryMine account, click on the "Gift Item Shop" button next to your theorem name and follow the instructions. 
</p>
<p>
We have also updated your TheoryMine certificate to a more friendly version! This should be compatible with most PDF readers. To get your new certificate, click on the "View Certificate" button next to your theorem name in your <a href="<?print $url?>?go=login">profile page</a>.
</p>

<p>
If you require any further information or assistance, please do not hesitate to contact us at <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
or have a look at our <a href="<? print $url ?>/?go=faq">F.A.Q webpage</a>.
</p>
<? include("pages/email/email_footer.php"); ?>
