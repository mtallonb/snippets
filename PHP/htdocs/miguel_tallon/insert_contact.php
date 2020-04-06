
<?php

include 'contact.class.php';

// LOAD DATABASE
$database = "exercise";
$username = "root";
$password = "";

$con=mysql_connect("localhost",$username,$password);
if (!$con)
  {
  die('Unable to connect: ' . mysql_error());
  }

mysql_select_db($database, $con);

//CREATION OF THE OBJECT
$obj = new contact($_POST["firstname"],$_POST["lastname"],$_POST["address"],$_POST["email"],$_POST["phone"]);
$obj->insert();
  
mysql_close();
?>
