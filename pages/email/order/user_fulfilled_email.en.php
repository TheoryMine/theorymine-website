<? include("pages/email/email_header.php"); ?>
<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']; 
 
$facebook_link = "http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.theorymine.co.uk%2F%3Fgo%3Dcert_image%26pid%3D".$val['id'] ."&amp;send=false&amp;layout=button_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21";

 ?>
Dear <? print $val['firstname']; ?> <? print $val['lastname']; ?>,  
<p>

We are pleased to let you know that our robot mathematician has discovered and named the theorem 

<?  print  $val['tname']; ?> </font><br/>

Thanks for your contribution to mathematical history!

</p>

<p>
You can now view your theorem by <a href="www.theorymine.co.uk/certificates/<?print htmlentities($val['certificate_id']);?>/certificate.pdf">downloading a pdf of the certificate</a>.
</p>
<p>
<a href="<?print $facebook_link?>">Share it on facebook</a>
</p>
<p>
Once you have downloaded it, you can print it, and if it was a gift, give it to the lucky person. 
</p>
<p>
You can download your theorem brochure, which will help you understand your theorem,   <a href="www.theorymine.co.uk/certificates/<?print htmlentities($val['certificate_id']);?>/brouchure.pdf">here</a>. 
</p>
<p>
You can also view the certificate and the theorem brochure by
<a href="<? print $url ?>?go=login">logging</a> in to your TheoryMine account
and clicking on the 
"View Certificate" button or the "View Brochure" button next to your named theorem.
</p>

<p>
You can now also purchase t-shirts, mugs and mouse-pads personalised with your theorem! To do so <a href="<? print $url ?>?go=login">log into</a> your TheoryMine account, click on the "Gift Item Shop" button next to your theorem name and follow the instructions. 
</p>
<p>
If you require any further information or assistance please do not hesitate in contacting us at <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a>
or have a look at our <a href="<? print $url ?>?go=faq">F.A.Q  page</a>.

</p>
<p>
<? include("pages/email/email_footer.php"); ?>
