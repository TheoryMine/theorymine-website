<? include("pages/email/email_header.php"); 
 $url = "http://theorymine.co.uk/"; ?>
<p>Hello,</p> 
<p>
We are emailing you to let you know that we have just created a brochure about your theorem! This brochure will guide you through the theory behind your theorem so that you can interpret and learn about it! The brochure is available for you to download free of charge! 
</p>

<p>
To download it <a href="<?print $url?>?go=login">log into</a> your TheoryMine account, click on the "View Brochure" button next to your theorem name and follow the instructions. 
</p>
<p>
If you require any further information or assistance, please do not hesitate to contact us at <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
or have a look at our <a href="<? print $url ?>/?go=faq">F.A.Q webpage</a>.
</p>
<? include("pages/email/email_footer.php"); ?>
