<?
$here_link = "?go=overview";
$header_title = "Home";
include 'pages/common_parts/header.php';
?>


<div class="main_buttons" id="main_buttons" >

<div class="about" align="center" style = "text-align:center;   margin-left:auto; margin-right: auto; width: 680px;">
  <font class="gold">
  <!--TheoryMine lets you name a personalised, newly discovered, mathematical theorems as a novelty gift.-->
  TheoryMine was a company that ran from 2009 to 2019 that let you name a personalised, newly discovered, mathematical theorems as a novelty gift. After 10 years it has now stopped trading, and in time we hope to write a little more about the project.
  </font>
</div>

<? // not logged in
if(!isset($_SESSION['id'])) {
?>

  <div class="about" align="center" style = "text-align:center;   margin-left:auto; margin-right: auto; width: 680px;">

  <br/><br/>

  <a href="?go=certificate_example">
  See an example theorem certificate that was generated.
  </a>
  </div>

  <!--
  <div  align="left" style = "text-align:left;  margin-top: 20px; margin-left:20px; width: 600px; font: 6px; ">
  <i>"During my time as an eager undergraduate mathematician, I'd often wonder what it would feel like to prove a truly new result and have my name immortalised in the mathematical history books. I thought that dream had died when I gave up maths to become a science writer, but Aron's theorem is now a reality, and I've got the certificate to prove it."</i>
  </div>
  -->

 <div class="preview" style = "text-align:center;   margin-left:auto; margin-right: auto; width: 35%;">
  <p>
    TheoryMine is no longer trading. We wish you all many happy mathematical discoveries, and hope you enjoyed the robot-found theorems we discusevered that you named.
  </p>
</div>

<? } // logged in

else {
?>

  <? include("pages/common_parts/discoveries-in-progress.php"); ?>

  <? include("pages/common_parts/discovered-theorems.php"); ?>

  <div class="clear"></div>
  <?
}


?>







<?

/*include("pages/common_parts/new_theorems.php");*/

?>
</p>
