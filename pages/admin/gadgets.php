<?php
restrict_is_admin();

$here_link = "?go=admin&s=gadgets";

// TO DO: change pid to cert; note people have been sent the old URL! 
$cert_id =sql_str_escape($_REQUEST['pid']);

/*
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php'); 


$thm_id =sql_str_escape($_REQUEST['pid']);

$thm_point = get_point($thm_id);
if($thm_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {

  $pdf_file_dir = 'gadgets/' . $thm_id; 
  $pdf_file_loc = $pdf_file_dir . '/' . $thm_id . '_thm.pdf'; 
  $pdf_file_loc2 = $pdf_file_dir . '/' . $thm_id . '_thy.pdf'; 
  //$pdf_file_loc2 = $pdf_file_dir . '/' . $thm_id . '_2.pdf';
  $img_file_loc = $pdf_file_dir . '/' . $thm_id . '_thm.jpg'; 
  $img_file_loc2 = $pdf_file_dir . '/' . $thm_id . '_thy.jpg';
}


   
    $thm_title= utf8_encode ( $thm_point['title']) ;
    $thm_body=  utf8_encode ($thm_point['body']);
    
    $thy_point = get_point_related_from($thm_point,"thy.","inthy.");
    $thy_body=  utf8_encode ($thy_point['body']);
    

    
    
    // create new PDF document
  $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf2 = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // remove default header/footer
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf2->setPrintHeader(false);
  $pdf2->setPrintFooter(false);
  
  // remove default header/footer
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf2->setPrintHeader(false);
  $pdf2->setPrintFooter(false);
  
  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf2->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  
  //set margins
  $pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf2->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  
  //set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf2->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  
  //set image scale factor
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf2->setImageScale(PDF_IMAGE_SCALE_RATIO);
  
  //set some language-dependent strings
  $pdf->setLanguageArray($l);
  $pdf2->setLanguageArray($l);
  
  // add a page
  $pdf->AddPage();
  
  $pdf->SetFont('freeserif', '', 50);
    
  $pdf->writeHTMLCell(0, 25, '', '', $thm_body, 0, 1, 0, true, 'C');
  
  // add a page
  $pdf2->AddPage();
  
  $pdf2->SetFont('times', '',30);
  $txt= "Let";
  $pdf2->Write($h=20, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
  $pdf2->SetFont('freeserif', '', 28);
  $pdf2->writeHTMLCell(0, 25, '', '', $thy_body, 0, 1, 0, true, 'C');
  $pdf2->SetFont('times', '',30 );
  $txt= "then";
  $pdf2->Write($h=5, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
  $pdf2->SetFont('times', '',25 );
  $txt= "";
  $pdf2->Write($h=5, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
  $pdf2->SetFont('freeserif', '', 28);
  $pdf2->writeHTMLCell(0, 25, '', '', $thm_body, 0, 1, 0, true, 'C');
  
  // ---------------------------------------------------------
  
  //Close and output PDF document
  //$contents = $pdf->Output($pdf_file_loc, 'S');
  //$contents2= $pdf2->Output($pdf_file_loc2, 'S');
  $contents = $pdf->Output("/Users/flaminiacavallo/gadgets/thm.pdf", 'F');
  $contents2= $pdf2->Output("/Users/flaminiacavallo/gadgets/thy.pdf", 'F');
  */
  
?> 
  
<script type="application/x-polyml">
val state = valOf (DOM.getElementById DOM.document "state");

fun string_of_file filename = 
    let val instream = TextIO.openIn filename
        fun reader str = 
            (case TextIO.inputLine instream of NONE => str
                | SOME s => reader (str ^ s))
        val str = reader "";
        val _ = TextIO.closeIn instream;
    in str end;


fun make_images () = 
  let 
    val current_value = DOM.getInnerHTML state
    val _ = DOM.setInnerHTML state ("Making images....");
    val img_file_dir = "/Users/flaminiacavallo/gadgets";
    val pdf_filename1 = img_file_dir ^ "/" ^ "thm.pdf";
    val img_filename1 = img_file_dir ^ "/" ^ "thm.jpg";
    val pdf_filename2 = img_file_dir ^ "/" ^ "thy.pdf";
    val img_filename2 = img_file_dir ^ "/" ^ "thy.jpg";
    val _ = DOM.setInnerHTML state  
    ("Making Images");
    val process = Unix.execute("/usr/local/bin/convert", 
      ["-gravity", "South", "-chop", "0x2000", "-density", "400", pdf_filename1, img_filename1]);
    val exit_status = Unix.reap process;
    val process = Unix.execute("/usr/local/bin/convert", 
      ["convert", "-gravity", "South", "-chop", "0x1000", "-density", "400", pdf_filename2, img_filename2]);
    val exit_status = Unix.reap process;
    val cmd_str1 = String.concat ["/usr/local/bin/convert ", pdf_filename1, " ", img_filename1]
    val cmd_str2 = String.concat ["/usr/local/bin/convert ", pdf_filename2, " ", img_filename2]
    val _ = DOM.setInnerHTML state  
    ("Done making images: " ^ pdf_filename1 ^ " => " ^ img_filename1 ^ " AND " 
       ^ pdf_filename2 ^ " => " ^ img_filename2 ^
      "<br><code>" ^ cmd_str1 ^ "<br/>"^ cmd_str2 ^"</code></br>");
  in () end;

val make_images_button = 
    valOf (DOM.getElementById DOM.document "make_images_button");
val l1 = DOM.addEventListener make_images_button DOM.click 
          (DOM.EventCallback (fn(_)=> make_images ()));
</script>

<p id="state">PDF created! </p>
<p>
<input id="make_images_button" type="button" value="make images">
</p> 


  

<form action="?go=admin&s=uploader&pid=<?print $cert_id?>" method="post"
enctype="multipart/form-data">
<label for="file_cert">Filename:</label>
<input type="file_cert" name="file_cert" id="file_cert" /> 
<br />
<label for="file_thm">Filename:</label>
<input type="file_thm" name="file_thm" id="file_thm" /> 
<br />
<label for="file_thy">Filename:</label>
<input type="file_thy" name="file_thy" id="file_thy" /> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>

  
<? 
 


?>
