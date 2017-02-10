<?php
require '../sn/database.php';
$pdo = Database::connect_fordrop();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Drop database if necessary
$dropDatabase = "DROP DATABASE IF EXISTS MyDB";
  echo nl2br("\n"); //Line break in HTML conversion
  echo "<b>Executing SQL statement: </b>";
  echo $dropDatabase; //Dispay statement being executed
  echo nl2br("\n");
  $q=$pdo->prepare($dropDatabase);
  if ($q->execute() === TRUE) {
      echo "<b><font color='green'>SQL statement performed correctly</b></font>";
  } else {
      echo "<b><font color='red'>Error executing statement: </b></font>" . $pdo->error;
  }
  Database::disconnect();
?>