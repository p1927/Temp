<body>

<?php
$failure="";

if(isset($_POST['cancel']))
{
header("Location:index.php");
return;
  }
if(isset($_POST['email'])||isset($_POST['pass'])){

  #$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
function check()
{  require_once 'database.php';
  $pdo = Database::connect();
  $salt='XyZzy12*_';
  $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users
      WHERE email = :em AND password = :pw');
  $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  Database::disconnect();
   $GLOBALS['row']=$row;
if ( $row !== false ){return 1;}
else {return 0;};
  }



if(strlen($_POST['email'])<1)
{ $failure=" Email is required";}
elseif(strpos($_POST['email'],"@")<1)
{ $failure=" Enter a valid E-mail.";}
elseif(strlen($_POST['pass'])<1)
{ $failure.=" Password is required";}
elseif(!check())
{$failure.=" Incorrect Password";}
else {
  session_start();
  $_SESSION['name'] = $row['name'];
  $_SESSION['user_id'] = $row['user_id'];

  header("Location:index.php");
  return;
}}
 ?>


<title>Pratyush Mishra</title>
<h1>Please Log In</h1>
<p class="error"><?=$failure?></p>
<form method="post">
<label  for="email">Email</label>
<input  style="margin-left:38px;" type="text" id="A" name="email"/><br>
<label for="pass">Password</label>
<input type="password" id="B" name="pass"/>
<br>
<input type="submit" onclick="return doValidate();" value="Log In"/>
<input type="submit" name="cancel" value="Cancel"/>
</form>
<style>
body{font-size: 1.2em;}
#A {margin-bottom: 10px;}
#B {margin-left: 10px;
margin-bottom: 10px;}
h1{color: grey;
font-family: "Arial";}
.error{color:red;
background-color: #f0fff0;}
</style>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        var pw = document.getElementById('B').value;
        var em = document.getElementById('A').value;
console.log("Validating Email = "+em);
if (em == null || em == "") {
    alert("Email must be filled out");
    return false;
}
else if (em.indexOf("@")<2) {
    alert("Enter a valid Email");
    return false;
}else;

        console.log("Validating Password="+pw);
        if (pw == null || pw == "") {
            alert("Password must be filled out");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</body>
