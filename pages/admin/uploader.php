<?php
restrict_is_admin();
$here_link = "?go=admin&s=uploader";

// Where the file is going to be placed 
//$target_path1 = "gadgets/".$thm_point."_thm.jpg";
//$target_path = "gadgets/".$thm_point."_thy.jpg";

$cert_id =sql_str_escape($_REQUEST['pid']);

//$thm_file_name= "images/" . $cert_id."_".$_FILES["file"]["name"];

$thm_file_dir="certificates/".$cert_id;

$thm_file_name = $thm_file_dir ."/".$_FILES["file"]["name"];
//$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
/*
  if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
      echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
      " has been uploaded";
  } else{
      echo "There was an error uploading the file, please try again!";
  }
  */
  
$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {
      if (($_FILES["file"]["type"] == "image/jpg")
      || ($_FILES["file"]["type"] == "image/jpeg")
      || ($_FILES["file"]["type"] == "application/pdf"))
    {
      
      if ($_FILES["file"]["error"] > 0)
      {
      echo "Error: " . $_FILES["file"]["error"] . "<br />";
      }
    else
      {
      echo "Upload: " . $thm_file_name. "<br />";
      echo "Type: " . $_FILES["file"]["type"] . "<br />";
      echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
      echo "Stored in: " . $_FILES["file"]["tmp_name"];
      }
    
      
    if(!file_exists($pdf_file_loc) 
       or ((file_exists($pdf_file_loc) and $_SESSION['userkind'] == 'admin'
            and $_REQUEST['fresh'] == "yes" ))) 
    {
     
  
      if ($_FILES["file"]["error"] > 0)
        {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        }
      else
        {
        echo "Upload: " . $thm_file_name . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
    
        if (file_exists( $thm_file_name))
          {
          echo $thm_file_name . " already exists. ";
          }
        elseif(is_dir($thm_file_dir) or mkdir($thm_file_dir))
          {
          move_uploaded_file($_FILES["file"]["tmp_name"],
           $thm_file_name);
          echo "Stored in: " .  $thm_file_name;
          ?>
          
          <a href="<?print $thm_file_name?>" target="_blank">View</a> | <a href="?go=admin&s=orders&search=<? print urlencode("p.id='" . $order_point['id'] . "'");?>&limit=1"> Back to Order </a>
          <?
          }
          
          else{
            die_at_noted_problem("no dir and couldn't create the certificate dir");
          }
        }
  
    }
    
  }
  else
    {
    echo "Invalid file";
    }
}  


?>
