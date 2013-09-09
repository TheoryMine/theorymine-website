<?php

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php'); 


// TO DO: change pid to cert; note people have been sent the old URL! 
$cert_id =sql_str_escape($_REQUEST['pid']);

$order_point = get_from_point_related_to_title($cert_id,'order.','has_certificate.');
if($order_point == null) {
  include("pages/bad_cert_id.php"); 
  exit();
} else {
  $name = $order_point['title'];
  $name = preg_replace('/[\s]+/', '_', $name);
  $name = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $name);
  $pdf_file_dir = 'certificates/' . $cert_id; 
  $pdf_file_loc = 
  $pdf_file_dir . '/TheoryMine_' . $name . '.pdf'; 
}

// if file doesn't exist, make dir
if(!file_exists($pdf_file_loc) 
   or ((file_exists($pdf_file_loc) and $_SESSION['userkind'] == 'admin'
        and $_REQUEST['fresh'] == "yes" ))) 
{
  if(is_dir($pdf_file_dir) or mkdir($pdf_file_dir)) { // if made directory successfully, make & save pdf
    $thm_point = get_point_related_from($order_point,'thm.','named.');
    //get_point_in_type($pid, 'thm.named.' );
    $thy_point = get_point_related_from($thm_point,"thy.","inthy.");
    $proof = get_point_related_to($thm_point,"proof.","proof.");
    
    $date = sqltimestamp_to_str($thm_point['time_stamp']);
    $thm_title= utf8_encode ( $thm_point['title']) ;
    $thm_body=  utf8_encode ($thm_point['body']);
    //$thm_body= utf8_encode ("try &alpha;");
    $proof_body = utf8_encode ("Proof outline: ". $proof['body']);
    $thy_title= utf8_encode ($thy_point['title']);
    $thy_body=  utf8_encode ($thy_point['body']);
    
    /*
    $thm_title=  'FLAMINIA THEOREM';
    $thm_body=  'a+b = b+a';
    $thy_title= 'Material World Theory';
    $thy_body=  'Set T = C1(Bool, Bool) | C2(T ); mw(C1(b1,b2),y) = y;  mw(C2(x),y) = C2(mw(x,y))';
    */
    
    // Extend the TCPDF class to create custom Header and Footer
    class MYPDF extends TCPDF {
      //Page header
      public function Header() {
        // full background image
        // store current auto-page-break status
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 0);
        $img_file = 'images/background3.jpg';
        $this->Image($img_file, 0, 0, 212, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        
      }
      
      public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-25);
        $this->SetFont('times', '', 13);
        $txt = "
        THIS THEOREM HAS BEEN NAMED AND RECORDED <br/> IN THE
        THEORYMINE DATABASE";
        $this ->writeHTMLCell(0, 0, 60, 247, $txt, 0, 1, 0, true, 'L');
        
        // Set font
        $this->SetFont('freeserif', 'I', 10);
        $website='<a href="http://www.theorymine.co.uk">www.theorymine.co.uk</a>';
        
        // Page number
        //$logo= 'images/logo2.png';
         global $date;
         $date_site = $date . "<br/>". $website;
        //$date = sqltimestamp_to_str($thm_point['time_stamp']);
        //$this ->Image($logo, 7, 230, 47, 47, 'PNG', 'http://www.theorymine.co.uk', '', true, 150, '', false, false, 0, false, false, false); 
        //$this ->setCellMargins(10,10,0,80);
        //$this -> Cell(0, 0, $date , 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this ->writeHTMLCell(0, 0, 17, 275, $date_site, 0, 1, 0, true, 'L');
      }
    }
    
    // create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    //set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(0);
    // $pdf->SetFooterMargin(0);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
    // remove default footer
    //$pdf->setPrintFooter(false);
    
    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    //set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    //set some language-dependent strings
    $pdf->setLanguageArray($l);
    
    // ---------------------------------------------------------
    
    // set font
    //$pdf->SetFont('times', '', 48);
    
    // add a page
    $pdf->AddPage();
    
    // Print a text
    //$html = 'TheoryMine
    //<p stroke="0.2" fill="true" strokecolor="yellow" color="blue" style="font-family:helvetica;font-weight:bold;font-size:26pt;">You can set a full page background.</p>';
    //$pdf->writeHTML($html, true, false, true, false, '');
    
    
    // set font
    $pdf->SetFont('times', 'bi', 50);
    
    // set some text to print
    $txt = "TheoryMine";
    // print a block of text using Write()
    $pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
    
    $pdf->SetFont('times', '', 16);
    $txt = "CERTIFICATE OF REGISTRY";
    $pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
    
    $txt="";
    $pdf->Write($h=15, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
    
    $pdf->SetFont('freeserif', 'b',30 );
    /*$txt = $thm_title;
    $pdf->Write($h=30, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);*/
    
    $pdf->writeHTMLCell(0, 15, '', '', $thm_title.':', 0, 1, 0, true, 'C');
    
    
    $pdf->SetFont('times', '',20 );
    $txt= "Let";
    $pdf->Write($h=20, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
    
    $pdf->SetFont('freeserif', '', 18);
    // print a cell
    //$pdf->writeHTMLCell(0, 55, '', '', $thy_body, 0, 1, 0, true, 'C');
    $pdf->writeHTML($thy_body, true, false, false, false, '');
    
    
    $pdf->SetFont('times', '',20 );
    $txt= "then";
    $pdf->Write($h=5, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
    
    $pdf->SetFont('times', '',20 );
    $txt= "";
    $pdf->Write($h=5, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
    
    $pdf->SetFont('freeserif', '', 28);
    //$pdf->SetFont('times', '', 24);
    /*$txt = $thm_body;
    $pdf->Write($h=25, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);*/
    
    $pdf->writeHTMLCell(0, 25, '', '', $thm_body, 0, 1, 0, true, 'C');
    
    
    
    $pdf->SetFont('freeserif', '', 18);
    $pdf->writeHTMLCell(0, 10, '', '', $proof_body, 0, 1, 0, true, 'C');
    
    
    
    
    // set JPEG quality
    //$pdf->setJPEGQuality(95);
    
    // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
    
    // Image example
    //$pdf->Image('images/logo2.png', 10, 220, 50, 50, 'PNG', 'http://www.theorymine.co.uk', '', true, 150, '', false, false, 0, false, false, false);
    
    // ---------------------------------------------------------
    //Close and output PDF document
    
    // name is actually ignored here, because S for saving to string
    $contents = $pdf->Output($pdf_file_loc, 'S');
    
    $f = fopen($pdf_file_loc, 'wb');
    if (!$f) {
     die_at_noted_problem('Unable to create output file: '.$pdf_file_loc);
    }
    fwrite($f, $contents, strlen($contents));
    fclose($f);
  } else {
    die_at_noted_problem("no dir and couldn't create the certificate dir"); 
  }
}

// File should have been created now, in which case we can send them the pdf...
if(file_exists($pdf_file_loc)) {
  if (ob_get_contents()) {
    die_at_noted_problem('Some data has already been output, can\'t send PDF file');
  }
  header('Content-Description: File Transfer');
  if (headers_sent()) {
    die_at_noted_problem('Some data has already been output to browser, can\'t send PDF file');
  }
  header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
  header('Pragma: public');
  header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
  // force download dialog
  header('Content-Type: application/force-download');
  header('Content-Type: application/octet-stream', false);
  header('Content-Type: application/download', false);
  header('Content-Type: application/pdf', false);
  // use the Content-Disposition header to supply a recommended filename
  header('Content-Disposition: attachment; filename="'.basename($name).'";');
  header('Content-Transfer-Encoding: binary');
  
  $handle = fopen($pdf_file_loc, "r");
  if($handle == null) {
    die_at_noted_problem("Couldn't open certificate");
  }
  $contents = fread($handle, filesize($pdf_file_loc));
  fclose($handle);

  header('Content-Length: '.strlen($contents));
  echo $contents;
} else {
  die_at_noted_problem("No such pdf"); 
}
?>
