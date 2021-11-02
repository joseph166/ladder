<?php
		
	require_once 'connection.php';
	connection::connect('localhost','root','','ladder');
	$conn = connection::getInstance();
	$conn = $conn->getConnection(); 
	$err = array();

	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}
	if(!empty($_SESSION['Uid'])){
	  header("Location: index.php");
	}
		if( $_SERVER["REQUEST_METHOD"] == "POST"){
			$name = mysqli_real_escape_string($conn,w3($_POST['name']));
			$lname = mysqli_real_escape_string($conn,w3($_POST['lname']));
			$user = mysqli_real_escape_string($conn,w3($_POST['user']));
			$pwd = mysqli_real_escape_string($conn,w3($_POST['pwd']));
			$con = $_POST['cpwd'];
			$date = date('m-d-Y');
			if(empty($name)&&empty($lname)&&empty($user)&&empty($pwd)&&empty($con)){
				array_push($err, "All empty!");
			}else{
				if(empty($name)||empty($lname)||empty($user)||empty($pwd)||empty($con)){
					array_push($err, "One or more field is empty!");
				}else if(strlen($name)<3 || strlen($name)>20){
					array_push($err, "Invalid Charecters in Name");
				}else if(strlen($lname)<3 || strlen($lname)>20){
					array_push($err, "Invalid Charecters in Last Name");
				}else if(strlen($user)<6 || strlen($user)>25){
					array_push($err, "Invalid Charecters in Username");
				}else if(strlen($pwd)>40||strlen($pwd)<6){
					array_push($err, "Invalid Charecters in Password");
				}else{
					if($pwd != $con){
						array_push($err, "Passwords are not the same!");
					}
				}
			}
			$check="SELECT username FROM user WHERE username='$user'";
			$check1 = $conn->query($check);
				if($check1->num_rows>0){
					array_push($err, "Username already exists.");
				}
			if(count($err)==0){
					$name = ucfirst($name);
					$lname = ucfirst($lname);
					$ip = random_int(157, 99999);
					$pwd = password_hash($pwd, PASSWORD_DEFAULT);
					$sql = "INSERT INTO user (name,lname,username,password,ip)VALUES('$name','$lname','$user','$pwd','$ip')";
					if($conn->query($sql)){
						$_SESSION['sign']="Account was created Successfully<br>Please Login.";
					}
				}
			}
function w3($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
		include 'assets/html/sign.html';

?>