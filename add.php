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

if (isset($_POST['first_name']))
{


  // function validatePosAdd($a,$b,$c) {
  //     for($i=1; $i<=9; $i++) {
  //         if ( ! isset($_POST[$a][$i]) ) continue;
  //         if ( ! isset($_POST[$b][$i]) ) continue;
  //
  //         $year = $_POST[$a][$i];
  //         $desc = $_POST[$b][$i];
  //         if ( strlen($year) == 0 || strlen($desc) == 0 ) {
  //             $failure="All fields are required";
  //             header("Location:add.php?msg=".$failure);
  //             return;
  //         }
  //
  //         if ( (! is_numeric($year)) && ($c=='Position') ) {
  //             $failure="Position year must be numeric";
  //               header("Location:add.php?msg=".$failure);
  //               return;
  //         }
  //         elseif ( (! is_numeric($year)) && ($c=='Education') ) {
  //             $failure="Education year must be numeric";
  //             header("Location:add.php?msg=".$failure);
  //               return;
  //         } else;
  //     }
  //     return true;
  // }

  if (
  strlen($_POST['first_name'])<1||
  strlen($_POST['last_name'])<1||
  strlen($_POST['email'])<1||
  strlen($_POST['headline'])<1||
  strlen($_POST['summary'])<1
  )
  {$failure="All fields are required";
  header("Location:add.php?msg=".$failure);
  }
  elseif(strpos($_POST['email'],"@")<1)
  { $failure="Email address must contain @";
  header("Location:add.php?msg=".$failure);
  }
  elseif(!validatePosAdd('year','desc','Position')){}
  elseif(!validatePosAdd('schyear','sc','Education')){}
else
{
  require_once 'database.php';
    $pdo = Database::connect();
    $stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );
    $profile_id = $pdo->lastInsertId();
$rank=1;

  insertPositiontable($pdo,$rank,$profile_id);
    // for($i=1; $i<=9; $i++) {
    //     if ( !isset ($_POST['year'][$i]) ) continue;
    //     if ( !isset($_POST['desc'][$i]) ) continue;
    //     $year = $_POST['year'][$i];
    //     $desc = $_POST['desc'][$i];
    // $stmt = $pdo->prepare('INSERT INTO position
    //         (profile_id, rank, year, description)
    //     VALUES ( :pid, :rank, :year, :desc)');
    //     $stmt->execute(array(
    //         ':pid' => $profile_id,
    //         ':rank' => $rank,
    //         ':year' => $year,
    //         ':desc' => $desc)
    //     );
    //     $rank++;}

$rank=1;
insertEducationTable($pdo,$rank,$profile_id);
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
        //     $rank++;}



      Database::disconnect();
$_SESSION['msg']="Profile Added";
echo "end";
header("Location:index.php");

}
}

 ?>
 <head>

<!-- <link rel="stylesheet"
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

  crossorigin="anonymous"></script> -->
</head>


<body>

<div class="container">
    <?php echo "<br><h1>Welcome ".$_SESSION['name']."</h1><br>";  ?>
<h3>Add Profile</h3>
<br><p class="err"><?=$failure?></p><br>
<form action="add.php" method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="5" cols="80"></textarea>
<section id="sch"></section>
<p>Education :  <button type="button" id="btnsch" class="btn btn-default">
    <span class="glyphicon glyphicon-plus"></span>
  </button></p>
<section id="plus"></section>
<p>Position :  <button type="button" id="btnplus" class="btn btn-default">
    <span class="glyphicon glyphicon-plus"></span>
  </button></p>
<p>
<input type="submit" value="Add" onclick="return validate();">
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
var operation=0;
var index=0;
var schoperation=0;
var schindex=0;
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
+"<p>School : <input type=\"text\" size=\"80\" name=\"sc["+schindex+"]\" class=\'school\' value=\'\' /></p></div>"
);

  schoperation++;
 }
else { alert("Maximum of nine education entries exceeded");}

$('.school').autocomplete({
source: "school.php"
});

});


</script>

</body>
