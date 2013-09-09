<? include("pages/email/email_header.php"); ?>
<? $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];  ?>
<meta http-equiv="content-type" content="text/html; charset=gb2312" />
尊敬的 <? print $val['firstname']; ?> <? print $val['lastname']; ?>,  
<p>

我们激动的告诉您，我们的机器数学家已经发现了一条新定理并命名为
<?  print  $val['tname']; ?> </font><br/>

感谢您对数学历史的贡献！

</p>

<p>
您现在可以点击<a href="www.theorymine.co.uk/certificates/<?print htmlentities($val['certificate_id']);?>/certificate.pdf">下载pdf证书</a>来查看您的定理. 当您下载后，您可以打印出来。如果这是您预备的一个礼品，就把它赠给那个幸运儿吧。
</p>
<p>
您可以点击<a href="www.theorymine.co.uk/certificates/<?print htmlentities($val['certificate_id']);?>/brouchure.pdf">这里</a>。下载手册，该手册将帮助您更好的理解您的定理。
</p>
<p>
您可以点击<a href="<? print $url ?>?go=login">登录</a> 进入您的系统并点击"查看证书"或者 "查看手册" 进行相应操作。
</p>

<p>
请注意不是所有的PDF浏览器都支持早证书中的数学符号。某些字符可能显示不正确。如果您使用Adobe Reader（可免费下载）就不会遇到类似问题。
</p>
<p>
您现在可以购买和您的定理相关的T恤，马克杯和鼠标垫！点击<a href="<? print $url ?>?go=login">登录</a>后，点击在您定理右边的“礼品中心”来继续。
</p>
<p>
如果您需要更多的信息或者帮助，请来联系我们 <a href="mailto:<? print $admin_email; ?>"><? print $admin_email; ?></a> 
或者查看 <a href="<? print $url ?>/?go=faq">常见问题</a>.
</p>
<p>
<? include("pages/email/email_footer.php"); ?>
