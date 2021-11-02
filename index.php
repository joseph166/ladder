<?php

$section = $_POST['section'] ?? $_GET['section'] ?? "Home";
include 'assets/config.php';
if(!isset($_SESSION['Uid'])){
  header("Location: login.php");
}
ob_start();
$err = array();
$noti_ch = null;
$ip = $_SESSION['ip'];
$date = date("m-d-Y");  
  require_once 'connection.php';
	connection::connect('localhost','root','','ladder');
	$conn = connection::getInstance();
	$conn = $conn->getConnection(); 
	include 'assets/html/nav.html';
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}
 $noti = $conn->query("SELECT * FROM challenge WHERE receiver='$ip'");
 if($noti->num_rows>0){
  while($count_noti = $noti->fetch_assoc()){
    $tc = $count_noti['ch_ip'];
    $noti_re = $conn->query("SELECT * FROM result WHERE Cid='$tc'");
    if($noti_re->num_rows>0){
          $noti_A = mysqli_fetch_array($noti_re)['winner'];
          if($noti_A==0){$noti_ch += 1;}
    }  
  }
 }
 switch($section){
        case "Challenge" :
         include 'php/'.$section.'.php';
          if(isset($_POST['start'])){
            $op = mysqli_real_escape_string($conn,w3($_POST['op']));
            $sel = $_POST['select'];
            if(empty($op)){
              $cho = "SELECT ip FROM user ORDER BY rand()";
              $q = $conn->query($cho);
              $ran = mysqli_fetch_array($q);
              $op = $ran['ip'];
              if($op==$ip){
              $chf = "SELECT ip FROM user ORDER BY rand()";
              $qi = $conn->query($chf);
              $r = mysqli_fetch_array($qi);
              $op = $r['ip'];
              }
            }
            $set_ch = new Challenge($conn,$ip,$op,$sel,$date);
            $ChallengeIp = $set_ch->Set_challenge();
            if($ChallengeIp==$ip){
              echo "<div class='text-center p-2 alert-primary'>You cannot challenge yourself.</div>";
            }else if($ChallengeIp=='not_found'){
              echo "<div class='text-center p-2 alert-primary'>The IP. you entered was not found.</div>";
            }else{
              $_SESSION['ch_ip']=$ChallengeIp;
              header("Location: index.php?section=Question");
            }
          }
         ;break;
        case "Ladder" : include 'php/'.$section.'.php';break;
        case 'Question':
         include 'php/'.$section.'.php';
         if(isset($_POST['start'])){      
          $_SESSION['ch_ip']=$_POST['ch_ip'];
        }
        $whichQ = new Question($conn,$_SESSION['ch_ip'],$ip);
        $whichQ->whichQ();
        ;break;
        case "Inbox" :include 'php/'.$section.'.php';break;
        case "Home" : $_SESSION['senderr']='';$_SESSION['quizerr']=''; include 'php/'.$section.'.php';break;
        case 'Received': include 'assets/html/received_ch.html';break;
        case 'Sent': include 'assets/html/sent_ch.html';break;
        case 'aboutus': include 'assets/html/about_us.html';break;
        case "Add_quiz" :
        if($_SESSION['user']=="iamadmin"){
              include 'php/'.$section.'.php';
            }else{
              include 'php/Home.php';
            };break;
        case "SendEmail" : 
        include 'php/'.$section.'.php';
        $ob = new SendEmail();
        showsent($conn,$ip);
        $ob->send($conn,$date,$ip);
        //deleting the sent messages.
        if(isset($_POST['delete_sent'])){
          $id = $_POST['senderIp'];
          $sql = "DELETE FROM message WHERE Mid='$id'";
          if($conn->query($sql)){
            $_SESSION['senderr']='';
            header("Location: index.php?section=SendEmail");
          }else{
            header("Location: index.php");
          }
        }
        break;
        case "Logout":  session_destroy(); header('Location: index.php');break;
        default : echo "<div class='text-secondary text-center p-1 h4'>404 :PATH NOT FOUND<hr> </div>";
  }
function w3($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
include 'assets/html/footer.html';

?>