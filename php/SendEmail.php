<?php
	class SendEmail{
		public function send($conn,$date,$ip){
			if($_SERVER['REQUEST_METHOD']=="POST" || isset($_POST['send'])){
				$msg = mysqli_real_escape_string($conn,w3($_POST['msg']));
				$rec = mysqli_real_escape_string($conn,w3($_POST['rec']));
				$check = "SELECT ip FROM user WHERE ip='$rec'";
				$c = $conn->query($check);
				if(empty($msg)&&empty($rec)){
					$_SESSION['senderr']='All empty';
					header("Location: index.php?section=SendEmail");
				}else if(empty($msg)||empty($rec)){
					$_SESSION['senderr']="A field is empty.";
					header("Location: index.php?section=SendEmail");
				}else if(strlen($msg)>300 || strlen($rec)>300){
					$_SESSION['senderr']='INVALID charecters!';
					header("Location: index.php?section=SendEmail");
				}else if($c->num_rows>0){
					$sendQuery ="INSERT INTO message (message,sender,receiver,sent_date)VALUES('$msg','$ip','$rec','$date')";
					if($conn->query($sendQuery)){
						echo "<h5 class='alert-warning p-1'><b>Message Sent!</b></h5>";
						$_SESSION['senderr']=null;
						header("Location: index.php?section=SendEmail");
					}else{
						$_SESSION['senderr']="Something went wrong!";
						header("Location: index.php?section=SendEmail");
					}
				}else{
					header("Location: index.php?section=SendEmail");
					$_SESSION['senderr']="User does not exists";				
				}

			}
		}
	}
	include 'assets/html/send.html';
?>