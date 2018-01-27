<h1>Welcome to Resume Registry Servces</h1>

<title>Pratyush Mishra</title>
<link rel="stylesheet" href="css/bootstrap.css">



<?php
  session_start();
if (isset($_SESSION['name']))
{ echo "<br><h1>Welcome ".$_SESSION['name']."</h1><br>";
  echo "<p>";
  echo "<a href=\"logout.php\">Logout</a>";
  echo "</p><br>";
  if (isset($_SESSION['msg'])){ echo "<p>".$_SESSION['msg']."</p><br>"; unset($_SESSION['msg']);}
echo "<p>";
echo "<a href=\"add.php\">Add New Entry</a>";
echo "</p>";

}
else{ echo "<p>";
echo "<a href=\"login.php\">Please log in</a>";
echo "</p>";}?>

<?php
 require_once 'database.php';
 $pdo = Database::connect();

 $sql = "SELECT * FROM profile;";
 $result=$pdo->query($sql);
 if($result){?>



<div class="row">
               <table class="table table-striped table-bordered">
                 <thead>
                   <tr>
                     <th>Name</th>
                     <th>HeadLine</th>
                     <?php  if (isset($_SESSION['name'])){ echo '<th>'."Action".'</th>';   }      ?>
                   </tr>
                 </thead>
                 <tbody>

<?php
                  foreach ($result as $row)
                   {
                           echo '<tr>';
                           echo '<td><a href=view.php?data='.$row['profile_id'].'>'.$row['first_name']." ".$row['last_name'].'</a></td>';
                           echo '<td>'.$row['headline'].'</td>';
                           if(isset($_SESSION['user_id'])&&$_SESSION['user_id']==$row['user_id']){ echo '<td><a href=edit.php?id='.$row['profile_id'].'>Edit</a>&nbsp;&nbsp;';
                             echo '<a href=delete.php?id='.$row['profile_id'].'>Delete</a></td>';}

                            echo '</tr>';

                  }
                  Database::disconnect();
                 ?>
                 </tbody>
           </table>
       </div>
<?php }?>


<p>
  <style>
body{font-size: 1.2em;
margin: 30px;}
  h1{color: grey;
  font-family: "Arial";}
  p{color:black;
  background-color: #f0f0f0;}
  table {margin-left: 15px;
    width: 50% !important;
  }
  </style>
