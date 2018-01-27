<?php
if(isset($_GET["data"]))
{
require_once 'database.php';
$pdo = Database::connect();
$stmt="Select * from profile where profile_id=".$_GET["data"];
$pr=$pdo->prepare($stmt);
$pr->execute();
$row = $pr->fetch(PDO::FETCH_ASSOC);
$sql = "SELECT * FROM Position where profile_id=".$_GET['data']." order by rank" ;
$poss=$pdo->query($sql);

$sql = "SELECT e.year,i.name FROM education as e join institution as i on e.institution_id=i.institution_id where profile_id=".$_GET['data']." order by rank" ;
$edu=$pdo->query($sql);

Database::disconnect();

}
 ?>
 <body>
 <div class="container">
<h1>Profile information</h1>
<p>First Name:
<?=$row['first_name'] ?></p>
<p>Last Name:
<?=$row['last_name'] ?></p>
<p>Email:
<?=$row['email'] ?></p>
<p>Headline:
<?=$row['headline'] ?></p>
<p>Summary:
<?=$row['summary'] ?></p>

<p>
<?php
echo "Position:<ul>";
foreach ($poss as $pos)
{ echo "<li>".$pos['year']." : ".$pos['description']."</li>";
  };
echo "</ul>";

?>
</p>

<p>
<?php


echo "Education:<ul>";
foreach ($edu as $ed)
{ echo "<li>".$ed['year']." : ".$ed['name']."</li>";};
 echo "</ul>";

?>
</p>

<a href="index.php">Done</a>
</div>
 <style>
body{font-size: 1.2em;
margin: 40px;}
 h1{color: grey;
 font-family: "Arial";}
 p{color:black;
 background-color: #f0f0f0;}
 </style>
</body>
