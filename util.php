<?php
function validatePosAdd($a,$b,$c) {
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST[$a][$i]) ) continue;
        if ( ! isset($_POST[$b][$i]) ) continue;

        $year = $_POST[$a][$i];
        $desc = $_POST[$b][$i];
        if ( strlen($year) == 0 || strlen($desc) == 0 ) {
            $failure="All fields are required";
            header("Location:add.php?msg=".$failure);
            return;
        }

        if ( (! is_numeric($year)) && ($c=='Position') ) {
            $failure="Position year must be numeric";
              header("Location:add.php?msg=".$failure);
              return;
        }
        elseif ( (! is_numeric($year)) && ($c=='Education') ) {
            $failure="Education year must be numeric";
            header("Location:add.php?msg=".$failure);
              return;
        } else;
    }
    return true;
}

function validatePosEdit($a,$b,$c) {
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST[$a][$i]) ) continue;
        if ( ! isset($_POST[$b][$i]) ) continue;

        $year = $_POST[$a][$i];
        $desc = $_POST[$b][$i];
        if ( strlen($year) == 0 || strlen($desc) == 0 ) {
            $failure="All fields are required";
          header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
            return;
        }

        if ( (! is_numeric($year)) && ($c=='Position') ) {
            $failure="Position year must be numeric";
            header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
              return;
        }
        elseif ( (! is_numeric($year)) && ($c=='Education') ) {
            $failure="Education year must be numeric";
            header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
              return;
        } else;
    }
    return true;
}

function exists($schname,$pdo){
  $stmt = $pdo->prepare('select institution_id from institution where name=:pid');
  $stmt->execute(array(':pid' => $schname));
  $id= $stmt->fetchAll();
  return $id[0]['institution_id'];
}

function insertschool($schname,$pdo){
  $stmt = $pdo->prepare('INSERT INTO institution (name) VALUES (:pid)');
  $stmt->execute(array(':pid' => $schname));
  $id=$pdo->lastInsertId();
  return $id;
}

function insertPositiontable($pdo,$rank,$profile_id){
  for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['year'][$i]) ) continue;
      if ( ! isset($_POST['desc'][$i]) ) continue;
      $year = $_POST['year'][$i];
      $desc = $_POST['desc'][$i];

  $stmt = $pdo->prepare('INSERT INTO Position
          (profile_id, rank, year, description)
      VALUES ( :pid, :rank, :year, :desc)');
      $stmt->execute(array(
          ':pid' => $profile_id,
          ':rank' => $rank,
          ':year' => $year,
          ':desc' => $desc)
      );
      $rank++;}}

function insertEducationTable($pdo,$rank,$profile_id){
      for($i=1; $i<=9; $i++) {
          if ( !isset ($_POST['schyear'][$i]) ) continue;
          if ( !isset($_POST['sc'][$i]) ) continue;
          $year = $_POST['schyear'][$i];
          $id=exists($_POST['sc'][$i],$pdo);
          $desc="";
          if($id){$desc=$id;}
            else {$desc=insertschool($_POST['sc'][$i],$pdo);}
      $stmt = $pdo->prepare('INSERT INTO education
              (profile_id, institution_id, rank, year)
          VALUES ( :pid,  :desc, :rank, :year)');
          $stmt->execute(array(
              ':pid' => $profile_id,
              ':rank' => $rank,
              ':year' => $year,
              ':desc' => $desc)
          );
          $rank++;}}

?>



<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
  <script

  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"

  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="

  crossorigin="anonymous"></script>
