<?php
	if($_SERVER['REQUEST_METHOD']=="POST" || isset($_POST['add'])){
		$q = mysqli_real_escape_string($conn,w3($_POST['qus']));
		$s = mysqli_real_escape_string($conn,w3($_POST['sol']));
		$w1= mysqli_real_escape_string($conn,w3($_POST['wr1']));
		$w2= mysqli_real_escape_string($conn,w3($_POST['wr2']));
		$w3= mysqli_real_escape_string($conn,w3($_POST['wr3']));
		$t = mysqli_real_escape_string($conn,w3($_POST['type']));
		if(empty($q)&&empty($s)&&empty($t)&&empty($w1)&&empty($w2)&&empty($w3)){
			$_SESSION['quizerr']="All empty!";
		}else{
			if(empty($q)||empty($s)||empty($t)||empty($w1)||empty($w2)||empty($w3)){
				$_SESSION['quizerr']="One field is left!";
			}else{
				$q = ucfirst($q);
				$s = ucfirst($s);
				$t = ucfirst($t);
				$k = ucfirst($k);
				$insert = "INSERT INTO question (question,Correct_a,wrong_b,wrong_c,wrong_d,type,added_date) VALUES ('$q','$s','$w1','$w2','$w3','$t','$date')";
				if($conn->query($insert)){
					$_SESSION['quizerr']='';
					header('Location: index.php?section=Add_quiz');
				}else{
					$_SESSION['quizerr']="SOMETHING WENT WRONG!?";
				}
			}
		}
	}
	if(isset($_POST['rem'])){
		$questionId = $_POST['iq'];
		$Del = $conn->query("DELETE FROM question WHERE Qid='$questionId'");
		if($Del){
			header("Location: index.php?section=Add_quiz");
		}else{
			die();
			session_destroy();
		}
	}
	include 'assets/html/add_quiz.html';
?>
