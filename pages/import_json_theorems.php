<?
/*
Handle a JSON object requesting to add a theorem to the theorem database.
*/

$postBody = file_get_contents('php://input');
// print('postBody: ' . $postBody . "\n\n");
$jsonObj = json_decode($postBody, true);

// print('json_encode($jsonObj): ' . json_encode($jsonObj) . "\n\n");
$returnObj = array('added_theorems' => 0, 'error' => null, 'added_theorems' => array());

if($jsonObj['pass'] != 'vtppassU1') {
  $returnObj['error'] = "bad/no password specified.";
} else if(sizeof($jsonObj['theorems']) <= 0) {
  $returnObj['error'] = "no theorems given to add";
} else {
  $query = ("SELECT p.* FROM $db_points as p WHERE p.point_type LIKE 'thy.%' AND p.body = '"
    . sql_str_escape($jsonObj['theory']) . "'");
  // print("Query: " . $query . "\n\n");
  $point = try_get_row("SELECT p.* FROM $db_points as p WHERE p.point_type LIKE 'thy.%' AND p.body = '"
    . sql_str_escape($jsonObj['theory']) . "'");
  if ($point != null) {
    $returnObj['error'] = 'theory already exists: ' . $point['id'];
  } else {
    $thy_title = sql_str_escape($jsonObj['thy_name_prefix'] . ':' . $jsonObj['function_number']);
    $thy_body = sql_str_escape($jsonObj['theory']);
    $thy_point_id = create_point(1, 'thy.' . $jsonObj['kind'] . '.', $thy_title, $thy_body, 'imported.');
    // print('thy_title: ' . $thy_title . "\n\n");
    // print('thy_body: ' . $thy_body . "\n\n");
    $returnObj['added_theory'] = $thy_point_id;

    $i = 0;
    $abody = 'imported.' . $thy_point_id;
    foreach($jsonObj['theorems'] as $thm) {
      $i++;
      // print('thm_statement: ' . $thm['statement'] . "\n\n");
      // print('thm_proof: ' . $thm['proof'] . "\n\n");
      $uploaded_name = 'untitled:' . $jsonObj['thy_name_prefix'] . ':' . $jsonObj['function_number'] . ':' . $i;
      $thm_id = create_point(1, 'thm.' . $jsonObj['kind'] . '.',
                             $uploaded_name, $thm['statement'], $abody);

      array_push($returnObj['added_theorems'], $thm_id);

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
    } // for each theorem.
  } // theory point is new
} // theorems > 0

header('Content-type: application/json');
print(json_encode($returnObj));
print("\n");
exit(0);
?>