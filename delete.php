<?php
session_start();
if(!isset($_SESSION['name']))
{die("<pNot logged in</p>");}

if(isset($_POST['cancel']))
{
header("Location:index.php");
return;
  }
  require_once 'database.php';
  $pdo = Database::connect();

if (isset($_POST['delid']))
{
  $stmt = $pdo->prepare('delete from Profile where profile_id='.$_GET['id']);
  $stmt->execute();
$_SESSION['msg']="Profile Deleted";
header("Location:index.php");
}


  $sql = "SELECT * FROM profile where profile_id=".$_GET['id'];
  $result=$pdo->query($sql);
  $result=$result->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
 ?>

<link rel="stylesheet" href="css/bootstrap.css">
 <title>Pratyush Mishra</title>
 <h1>Delete Profile</h1>
 <div class=" panel panel-warning panel-heading ">
 <p>First Name: <?=$result['first_name']    ?></p>
 <p>Last Name: <?=$result['last_name']    ?></p>
</div>
 <form method="post">
<input type="hidden" name="delid" value="<?= $_GET['id']?>">
 <input type="submit" class="btn btn-danger" value="Delete"/>
 <input type="submit" class="btn btn-default" name="cancel" value="Cancel"/>
 </form>
 <style>
 body{font-size: 1.6em !important;
 margin: 80px;}
  h1{color: grey;
 font-family: "Arial";}
div{background-color: lightyellow!important;
width:30%;}
 </style>
