<?php
session_start();
if(!isset($_SESSION['name']))
{die("<pACCESS DENIED</p>");}

if(isset($_POST['cancel']))
{
header("Location:index.php");
return;
  }
require_once 'util.php';


$failure="";
if(isset($_GET['msg']))
{$failure=$_GET['msg'];};
require_once 'database.php';
$pdo = Database::connect();

if (isset($_POST['first_name']))
{
  // function validatePosEdit($a,$b,$c) {
  //     for($i=1; $i<=9; $i++) {
  //         if ( ! isset($_POST[$a][$i]) ) continue;
  //         if ( ! isset($_POST[$b][$i]) ) continue;
  //
  //         $year = $_POST[$a][$i];
  //         $desc = $_POST[$b][$i];
  //         if ( strlen($year) == 0 || strlen($desc) == 0 ) {
  //             $failure="All fields are required";
  //           header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
  //             return;
  //         }
  //
  //         if ( (! is_numeric($year)) && ($c=='Position') ) {
  //             $failure="Position year must be numeric";
  //             header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
  //               return;
  //         }
  //         elseif ( (! is_numeric($year)) && ($c=='Education') ) {
  //             $failure="Education year must be numeric";
  //             header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
  //               return;
  //         } else;
  //     }
  //     return true;
  // }
  //
  // function exists($schname,$pdo){
  //   $stmt = $pdo->prepare('select institution_id from institution where name=:pid');
  //   $stmt->execute(array(':pid' => $schname));
  //   $id= $stmt->fetchAll();
  //   var_dump($id);
  //   return $id[0]['institution_id'];
  // }
  // function insertschool($schname,$pdo){
  //   $stmt = $pdo->prepare('INSERT INTO institution (name) VALUES (:pid)');
  //   $stmt->execute(array(':pid' => $schname));
  //   $id=$pdo->lastInsertId();
  //   return $id;
  // }
  if (
  strlen($_POST['first_name'])<1||
  strlen($_POST['last_name'])<1||
  strlen($_POST['email'])<1||
  strlen($_POST['headline'])<1||
  strlen($_POST['summary'])<1
  )
  {$failure="All fields are required";
  header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
  }
  elseif(strpos($_POST['email'],"@")<1)
  { $failure="Email address must contain @";
  header("Location:edit.php?id=".$_GET['id']."&msg=".$failure);
  }
  elseif(!validatePosEdit('year','desc','Position')){}
  elseif(!validatePosEdit('schyear','sc','Education')){}
else
{


    $stmt = $pdo->prepare('update Profile set first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su where profile_id=:pid;');
    $stmt->execute(array(
        ':pid' => $_GET['id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );
    $profile_id = $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM Position
       WHERE profile_id=:pid');
   $stmt->execute(array( ':pid' => $profile_id));

  $rank=1;
  insertPositiontable($pdo,$rank,$profile_id);
  // function insertPositiontable(){
  //   for($i=1; $i<=9; $i++) {
  //       if ( ! isset($_POST['year'][$i]) ) continue;
  //       if ( ! isset($_POST['desc'][$i]) ) continue;
  //       $year = $_POST[year][$i];
  //       $desc = $_POST[desc][$i];
  //
  //   $stmt = $pdo->prepare('INSERT INTO Position
  //           (profile_id, rank, year, description)
  //       VALUES ( :pid, :rank, :year, :desc)');
  //       $stmt->execute(array(
  //           ':pid' => $profile_id,
  //           ':rank' => $rank,
  //           ':year' => $year,
  //           ':desc' => $desc)
  //       );
  //       $rank++;}}

        $stmt = $pdo->prepare('DELETE FROM Education
           WHERE profile_id=:pid');
       $stmt->execute(array( ':pid' => $profile_id));
      $rank=1;

      insertEducationTable($pdo,$rank,$profile_id);
      // function insertEducationTable(){
      // for($i=1; $i<=9; $i++) {
      //     if ( !isset ($_POST['schyear'][$i]) ) continue;
      //     if ( !isset($_POST['sc'][$i]) ) continue;
      //     $year = $_POST['schyear'][$i];
      //     $id=exists($_POST['sc'][$i],$pdo);
      //     $desc="";
      //     if($id){$desc=$id;}
      //       else {$desc=insertschool($_POST['sc'][$i],$pdo);}
      // $stmt = $pdo->prepare('INSERT INTO education
      //         (profile_id, institution_id, rank, year)
      //     VALUES ( :pid,  :desc, :rank, :year)');
      //     $stmt->execute(array(
      //         ':pid' => $profile_id,
      //         ':rank' => $rank,
      //         ':year' => $year,
      //         ':desc' => $desc)
      //     );
      //     $rank++;}}

$_SESSION['msg']="Profile Updated";
 header("Location:index.php");

}
}

$sql = "SELECT * FROM profile where profile_id=".$_GET['id'];
$result=$pdo->query($sql);
$result=$result->fetch(PDO::FETCH_ASSOC);
$sql = "SELECT * FROM Position where profile_id=".$_GET['id']." order by rank" ;
$poss=$pdo->query($sql);
$sql = "SELECT * FROM education as e join institution as i on e.institution_id=i.institution_id where profile_id=".$_GET['id']." order by rank" ;
$edu=$pdo->query($sql);
$edu=$edu->fetchAll();
  Database::disconnect();
 ?>

 <head>
<!--
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
</head> -->

<body>

<div class="container">
    <?php echo "<br><h1>Welcome ".$_SESSION['name']."</h1><br>";  ?>
<h3>Edit Profile</h3>
<br><p class="err"><?=$failure?></p><br>
<form action="edit.php?id=<?=$_GET['id']?>" method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?=$result['first_name']?>" /></p>
<p>Last Name:
<input type="text" name="last_name" size="60"  value="<?=$result['last_name']?>" /></p>
<p>Email:
<input type="text" name="email" size="30" value="<?=$result['email']?>"  /></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?=$result['headline']?>"  /></p>
<p>Summary:<br/>
<textarea name="summary" rows="5" cols="80" ><?=$result['summary']?></textarea></p>
<p>
<?php

 foreach ($edu as $a)
 {
   echo "<div id=\"edu["
   .$a['rank'].
   "]\"><p>Year: <input type=\"text\" name=\"schyear["
   .$a['rank'].
   "]\" size=\"40\" value=\"".$a['year']."\"/> <button type=\"button\" id=\"schminus["
   .$a['rank'].
   "]\" onclick=\"divremover("
   .$a['rank'].",'edu'".
   "); return false;\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-minus\"></span></button> </p>"
   ."<p>School : <input type=\"text\" size=\"80\" name=\"sc[".$a['rank']."]\" class=\"school\" value=\"".$a['name']."\" /></p></div>";

}
 ?>
</p>
<section id="sch"></section>
<p>Education :  <button type="button" id="btnsch" class="btn btn-default">
    <span class="glyphicon glyphicon-plus"></span>
  </button></p>

<p>
<?php

 foreach ($poss as $pos)
 {
   echo "<div id=\"pos["
   .$pos['rank'].
   "]\"><p>Year: <input type=\"text\" name=\"year["
   .$pos['rank'].
   "]\" size=\"40\" value=\"".$pos['year']."\"/>  <button type=\"button\" id=\"btnminus["
   .$pos['rank'].
   "]\" onclick=\"divremover(".$pos['rank'].","."'pos'"."); return false;\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-minus\"></span>   </button> </p> <p> <textarea name=\"desc["
   .$pos['rank'].
   "]\" rows=\"5\" cols=\"80\">".$pos['description']."</textarea></p></div>";

}
 ?>
</p>



<section id="plus"></section>
<p>Position :  <button type="button" id="btnplus" class="btn btn-default">
    <span class="glyphicon glyphicon-plus"></span>
  </button></p>
<p>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
<style>
.err{ color:red;
  background-color: lightgrey;
}

</style>
<script>
var operation=<?=count($poss);?>;
var index=<?=count($poss);?>;
var schoperation=<?=count($edu);?>;
var schindex=<?=count($edu);?>;

function divremover(i,y){
document.getElementById(y+'['+i+']').remove();
};

$('#btnplus').click(function(){
if (operation<9)
{ index++;
  $('#plus').append(
"<div id=\"pos["
+index+
"]\"><p>Year: <input type=\"text\" name=\"year["
+index+
"]\" size=\"40\"/>  <button type=\"button\" id=\"btnminus["
+index+
"]\" onclick=\"divremover("+index+","+"'pos'"+"); return false;\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-minus\"></span>   </button> </p> <p> <textarea name=\"desc["
+index+
"]\" rows=\"5\" cols=\"80\"></textarea></p></div>"
);

  operation++;
 }
else { alert("Maximum of nine position entries exceeded");}

});

$('#btnsch').click(function(){
if (schoperation<9)
{ schindex++;
  $('#sch').append(
"<div id=\"edu["
+schindex+
"]\"><p>Year: <input type=\"text\" name=\"schyear["
+schindex+
"]\" size=\"40\"/>  <button type=\"button\" id=\"schminus["
+schindex+
"]\" onclick=\"divremover("
+schindex+
","
+"'edu'"+
"); return false;\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-minus\"></span></button> </p>"
+"<p>School : <input type=\"text\" size=\"80\" name=\"sc["+schindex+"]\" class=\"school\" value=\"\" /></p></div>"
);

  schoperation++;
  $('.school').autocomplete({
  source: "school.php"
  });
 }
else { alert("Maximum of nine education entries exceeded");}


});

$('.school').autocomplete({
source: "school.php"
});


</script>
</body>
