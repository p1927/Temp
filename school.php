<?php
header('Content-Type: application/json');
require_once 'database.php';
  $pdo = Database::connect();
  $stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix');
$stmt->execute(array( ':prefix' => $_GET['term']."%"));
$retval = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
$retval[] = $row['name'];
}
echo(json_encode($retval, JSON_PRETTY_PRINT));

?>
