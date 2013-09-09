<?php

if(isset($_SESSION['lang']))
{
  $lang = $_SESSION['lang'];
  switch($lang){
  case "en":
  require 'languages/language.en.php'; // where the content in english is stored
  break;
  case "cn":
  require("languages/language.cn.php"); // where the content in chinese is stored
  break;
    case "sp":
  require("languages/language.sp.php"); // where the content in spanishe is stored
  break;
  }
}
else{
 require 'languages/language.en.php'; 
}
$thislang = $lang;

?>
