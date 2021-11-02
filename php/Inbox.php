<?php

	$x = "SELECT * FROM message WHERE receiver='$ip' ORDER BY sent_date";
	$y = $conn->query($x);
	// this deletes the inbox
	if(isset($_POST['submit_inbox'])){
		$id = $_POST['del_Inbox'];
		$sql = "DELETE FROM message WHERE Mid='$id'";
		if($conn->query($sql)){
			header('Location: index.php?section=Inbox');
		}else{
			header('Location: index.php');
		}
	}
	if(isset($_POST['read'])){
		include 'assets/html/read.html';
	}
	include 'assets/html/inbox.html';
?>