<html>
<head>
<title>EXERCISE</title>
</head>
<body>

<?php 

$file=$_SERVER['QUERY_STRING']; //Get XML filename from the URL (after ?)

error_reporting(E_ERROR | E_PARSE); // Avoid warning below for empty inicialization

$xml->firstname="";
$xml->lastname="";
$xml->address="";
$xml->email="";
$xml->phone="";
	
if (file_exists($file)){
//Read the XML and create an object with its content
	$xml = simplexml_load_file($file) or die ("Unable to load XML file!");	
	echo "Data imported from XML file:";
}else{
	if ($file!=""){
		echo "Check XML file name.";
	}
	else{
		echo "Normal use: User inputs.";
	}
}

?>

<form action="insert_contact.php" method="post">
Firstname: <input type="text" name="firstname" value= "<?php echo $xml->firstname; ?>" ><br />
Lastname: <input type="text" name="lastname" value= "<?php echo $xml->lastname; ?>" ><br />
Address: <input type="text" name="address" value= "<?php echo $xml->address; ?>" ><br />
Email: <input type="text" name="email" value= "<?php echo $xml->email; ?>" ><br />
Phone: <input type="text" name="phone" value= "<?php echo $xml->phone; ?>" ><br />
<input type="submit">
</form>



</body>
</html>