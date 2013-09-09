<? if(! isset($header_printed)) {
    include('pages/language.php');
$header_title = set_default($header_title, "Name a Theorem");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<!--<meta http-equiv="content-type" content="text/html; charset=windows-1250">-->
<meta http-equiv='Content-Type' content='text/html; charset=<?php print $thislang['txt_charset']; ?>'>
<meta name="description" content="The gift of personalised, newly
discovered theorems: immortalise in mathematics your loves, friends,
teachers, and even your favourite pets by naming a new mathematical theorem.">
<meta name="keywords" content="theory mine, theorymine, name theorem, name theorems, theory, theorem, buy theorem, buy, mathematics, maths gift, math gift,
maths, math, novelty, new, gift, personalised, personalized, personal, immortalise, geometry, proof, proved, truth, purchase, sums"> 

<html>
<head>
<title>TheoryMine | <? print($header_title); ?></title>
<link rel=StyleSheet href="css/style.css" type="text/css" media=screen>
<link rel=StyleSheet href="css/menu.css" type="text/css" media=screen>
<link rel=StyleSheet href="css/footer.css" type="text/css" media=screen>
<link rel=StyleSheet href="css/headers.css" type="text/css" media=screen>
<link rel=StyleSheet href="css/theorem.css" type="text/css" media=screen>
<!-- <link rel="SHORTCUT ICON" href="images/favico.ico"> --> 
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<meta property="og:title" content="TheoryMine" />
<meta property="og:type" content="company" />
<meta property="og:url" content="http://www.theorymine.co.uk/" />
<meta property="og:image" content="http://www.theorymine.co.uk/certificates/34986135b3bf12e48d86b6cbf821032a3553/certificate_image.jpg" />
<meta property="og:site_name" content="TheoryMine" />
<meta property="fb:admins" content="61009781" />
</head>
<body>


<?
if($_SESSION['userkind']  == "admin") {
  ?> 
  <ul class="admin-toolbar">
  <li <? if($subpage == "setup") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=setup">setup</a></li>
 
  <li <? if($subpage == "users") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=users">users</a></li>

  <li <? if($subpage == "points") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=points">points</a></li>

  <li <? if($subpage == "rels") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=rels">rels</a></li> 
<!-- 
  <li <? if($subpage == "gifts") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=gifts">gifts</a></li> 
-->
  <li <? if($subpage == "certificates") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=certificates">certificates</a></li>

  <li <? if($subpage == "thm") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=thm">thm</a></li>

  <li <? if($subpage == "thy") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=thy">thy</a></li>

  <li <? if($subpage == "orders") { ?>class="selected"<? } ?>>
  <a href="?go=admin&s=orders">orders</a></li>
  

  <? if(in_debug_mode()) {
    ?>debug=on <a href="<? print($here_link); ?>&debug=off">(toggle)</a><?
  } else { 
    ?>debug=off <a href="<? print($here_link); ?>&debug=on">(toggle)</a><?
  }
  ?></ul><?
}
?>
</div>
<? // don't run google-analytics for internal pages 
   // (danger of leaking information, such JS password sniffing)
if($page != 'admin' and ! in_debug_mode()
   and $_SESSION['userkind'] != 'admin' 
   and $_SESSION['userkind'] != 'editor') { ?>
<!-- GOOGLE --> 
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-15853303-1");
pageTracker._trackPageview();
} catch(err) {}</script>
<? } ?>

<div id="header">
<p>
<table  style="margin-left: 48px" >
<tr>
<td>
<div class="logo-header">
  <a href="?go=overview">
  <img class="logo" src="images/logo.png" width="320px"   alt="TheoryMine"><sup>TM</sup></a><br>
  <font class="gold"><!--Personalized mathematical theorems--><?print $thislang['header_tagline'];?></font>
</div>
</td>
<td>
<div class="mascots">
  <img src="images/mathematicians_Christmas.png" align="middle"style="width:39%;"> ... <!--you could be next!--><?print $thislang['header_next'];?>
</div>
</td>
</tr>
</table>

<!--
<div class="NEWS"><font style="font-style:italic;"><?print $thislang['header_new'];?></div> -->

<?
  include 'pages/common_parts/main_links_header.php';
?>
</p>

<?
/* display info messages for user */
if($msgs != null){
  ?><div class="msgs">
  <? if($here_link != null) { ?>
    <div class="msg-tools"><a href="<? print($here_link); ?>"><!--clear messages--><?print $thislang['header_clear'];?></a></div>
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
 






</div>
<div class="page">
<!-- UNCOMMENT TO SEE debug mode USERNAME details
<?
if(in_debug_mode()) {
  ?> 
  <div class="debug">
  <? if(isset($_SESSION['id'])){
    ?>- UID: <? print $_SESSION['id']; ?>; user_name: <? print $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?>;       user_kind: <? print $_SESSION['userkind']; ?>
  <? } 
  ?></div><?
}
?>
-->
<? 
$header_printed = true;
}
?>

