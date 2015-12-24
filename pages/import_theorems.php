<?
if($_REQUEST['pass'] == 'vtppassU1') {

$header_title = "Theory Upload";
include 'pages/common_parts/header.php';

function fix_bad_init_file_contents($contents) {
  $contents = preg_replace('/\\<\\?/','',$contents);
  $contents = preg_replace('/\\?\\>/','',$contents);
  $contents = preg_replace('/([\\(, ])([a])([\\), \\n\\<\\\'])/',
    '\\1x\\3',$contents);
  $contents = preg_replace('/([\\(, ])([b])([\\), \\n\\<\\\'])/',
    '\\1y\\3',$contents);
  $contents = preg_replace('/([\\(, ])([c])([\\), \\n\\<\\\'])/',
    '\\1z\\3',$contents);
  $contents = preg_replace('/([\\(, ])([d])([\\), \\n\\<\\\'])/',
    '\\1w\\3',$contents);
  return $contents;
}

$thy_name_prefix = $_REQUEST['thy_name_prefix'];

if($_REQUEST['act']=="upload"){
  if ($_FILES['theorems_file']['error'] > 0) {
    print("Error: " . $_FILES['theorems_file']['error'] . "<br />");
  } else {
    $uploaded_name = $thy_name_prefix . "-" .
        basename($_FILES['theorems_file']['name'],".php");
    $tmp_fname = $_FILES['theorems_file']['tmp_name'];

    if($handle = fopen($tmp_fname, 'r')) {
      $contents = fread($handle, filesize($tmp_fname));
      fclose($handle);
      $contents_orig = $contents;
      $contents = fix_bad_init_file_contents($contents);
      eval($contents);

      if(sizeof($theorems) > 0) {
        $point = try_get_row("SELECT p.* FROM $db_points as p WHERE p.body = '"
            . sql_str_escape($theory) . "'");
        if($point == null) {
          $thy_point_id = create_point(1, 'thy.unnamed.',
            sql_str_escape($uploaded_name), sql_str_escape($theory), 'imported.');
          $i = 0;
          $abody = 'imported.' . $thy_point_id;
          foreach($theorems as $thm) {
            $i ++;
            $thm_id = create_point(1, 'thm.unnamed.',
              $uploaded_name . '-' . $i, $thm['statement'], $abody);

            $proof_id = create_point(1, 'proof.',
              $thm['statement'], $thm['proof'], $abody);

            $proof_rel = array('src_obj_id' => $proof_id,
                           'dst_obj_id' => $thm_id,
                           'relation_type' => 'proof.');
            $proof_rel_id = create_rel(1, $proof_rel, $abody);

            $inthy_rel = array('src_obj_id' => $thm_id,
                           'dst_obj_id' => $thy_point_id,
                           'relation_type' => 'inthy.');
            $inthy_rel_id = create_rel(1, $inthy_rel, $abody);
          }
          ?><div class='good'>Added theory with id: <? print($thy_point_id) ?>; and <? print(sizeof($theorems)); ?> theorems.</div><?
        } else {
          ?><div class='warning'>Theory has already been uploaded with id: <? print($point['id']) ?></div><?
        }
      } else {
        ?><div class='warning'>No theorems in theory; skipping.</div><?
      }

      ?>
      <div class="simple-border">
      <div class="theorem-view">
      <div class="theorem-name-title">Theorems for <? print($_FILES['theorems_file']['name']); ?></div>
      Let
      <div class="theorem-theory"><?
          print($theory);
      ?></div>
      then:<ul><?
      $i = 0;
      foreach($theorems as $thm) {
        $i ++;

        ?>
        <li><div class="theorem-statement"><? print($uploaded_name . '-' . $i); ?>: <? print($thm['statement']); ?></div>
            <div class="theorem-proof">Proof outline: <? print($thm['proof']); ?></div>
        </li><?
      }
      ?></ul></div></div><?

    } else {
      ?><div class="warning">fopen failed<? print($_FILES['theorems_file']['tmp_name']); ?></div><?
    }
  }
}
?><p>Upload: <? print($_FILES['theorems_file']['name']); ?><br />
Type: <? print($_FILES['theorems_file']['type']); ?> <br />
Size: <? print($_FILES['theorems_file']['size'] / 1024); ?> Kb<br />
Stored in: <? print($_FILES['theorems_file']['tmp_name']); ?> <br />
</p>
<div class="code"><?
      print(htmlentities($contents_orig));
?></div><?

?>
<h3>Upload Theory and Theorems</h3>
<p>
<form enctype="multipart/form-data" action="" method="POST">
<input type="hidden" name="act" value="upload" />
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
Theory prefix name (e.g. rec datatype) <input name="thy_name_prefix" type='input' size="60" value="<? print($thy_name_prefix); ?>"/><br />
Choose a file to upload: <input name="theorems_file" type='file' size="60" value="<? print(htmlentities($_FILES['theorems_file']['name'])); ?>"/><br />
<input type="submit" value="Upload File" />
</form>
</p>
<p>
Some handy SQL delete syntax:
<pre>
DELETE p FROM vtp_points as p, vtp_actions as a
WHERE p.action_id = a.id
AND a.action_body LIKE 'imported.%'
</pre>
</p>
<?
} else {
  new_msg("");
  include 'pages/common_parts/header.php';
}
?>
