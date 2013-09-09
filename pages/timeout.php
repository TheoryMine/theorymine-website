<?
  $header_title = "Timed out";
  include 'pages/common_parts/header.php';
  force_logout();
?>

<h1><!--You have been logged out--><?print $thislang['timeout_title'];?></h1>

<p><!--You get automatically logged out after--><?print $thislang['timeout_p1'];?> <? print(session_cache_expire()); ?><!--minutes of inactivity, or if you try to access a restricted page. To continue, <a target="_blank" href="?go=login">login again</a>; this login link will open in another window and then you can try to reload this page.--><?print $thislang['timeout_p2'];?></p>

 
