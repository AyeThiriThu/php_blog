<?php 
$servername="localhost";
$dbname="blog";
$username="root";
$pass="";
$dns="mysql:host=$servername;dbname=$dbname";
$pdo=new PDO($dns,$username,$pass);
try{
	$conn=$pdo;
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	//echo "connected successfully";
}catch(PDOException $e){
	echo "connection fail".$e->getMessage();
}


 ?>