<?php
class Question{

	public $conn;
	public $ch_ip;
	public $ip;

	function __construct($conn,$ch_ip,$ip){
		$this->conn = $conn;
		$this->ch_ip = $ch_ip;
		$this->ip = $ip;
	}

	function whichQ(){
		$take_q = "SELECT * FROM challenge WHERE ch_ip ='$this->ch_ip'";
		$tq = $this->conn->query($take_q);
		while ($ea = $tq->fetch_assoc()) {
			$question1 = $ea['question1'];
			$question2 = $ea['question2'];
			$question3 = $ea['question3'];
		}

		$takeQ1 = "SELECT * FROM question WHERE Qid='$question1'";
		$takequery1 = $this->conn->query($takeQ1);
		$tall1 = mysqli_fetch_array($takequery1);
		$wr1 = [$tall1['Correct_a'],$tall1['wrong_b'],$tall1['wrong_c'],$tall1['wrong_d']];
		shuffle($wr1);

	 	$takeQ2 = "SELECT * FROM question WHERE Qid='$question2'";
		$takequery2 = $this->conn->query($takeQ2);
		$tall2 = mysqli_fetch_array($takequery2);	
		$wr2 = [$tall2['Correct_a'],$tall2['wrong_b'],$tall2['wrong_c'],$tall2['wrong_d']];
		shuffle($wr2);
		$score=0;
		//where we go!
	 	$takeQ3 = "SELECT * FROM question WHERE Qid='$question3'";
		$takequery3 = $this->conn->query($takeQ3);
		$tall3 = mysqli_fetch_array($takequery3);	
		$wr3 = [$tall3['Correct_a'],$tall3['wrong_b'],$tall3['wrong_c'],$tall3['wrong_d']];
		shuffle($wr3);	
		if(isset($_POST['submit'])) {
    		 $an1=$_POST['answer1'];
    		 $an2=$_POST['answer2'];
    		 $an3=$_POST['answer3'];
    		 if($an1==$tall1['Correct_a']){
    		 	$score = 1;
    		 }
    		 if($an2==$tall2['Correct_a']){
    		 	$score+=1;
    		 }
    		 if($an3==$tall3['Correct_a']){
    		 	$score+=1;
    		 }
    		 $ck = "SELECT * FROM score WHERE ch_ip='$this->ch_ip' AND ip='$this->ip'";
    		 $qck = $this->conn->query($ck);
    		 if($qck->num_rows>0){
    		 	$answererr = "You already submitted your answers";
    		 }else{
                $inser = "INSERT INTO score (ch_ip,ip,Score) VALUES ('$this->ch_ip','$this->ip','$score')";
                if($this->conn->query($inser)){
                    $good = "Answer Submitted successfully";
                }else{
                    $answererr = "Error ! DB not working ! :(";
                }
    		 	$ckr = "SELECT * FROM result WHERE Cid='$this->ch_ip'";
    		 	$ckq = $this->conn->query($ckr);
    		 	if($ckq->num_rows>0){
    		 		//you are second to submit
    		 		$insertr = "UPDATE result SET second='$this->ip' WHERE Cid='$this->ch_ip'";
    		 		$this->conn->query($insertr);
    		 		$sl = "SELECT * FROM challenge WHERE ch_ip='$this->ch_ip'";
    		 		$slq = $this->conn->query($sl);
    		 		while($take_sc=$slq->fetch_assoc()){
    		 			$f = $take_sc['sender'];
    		 			$s = $take_sc['receiver'];
    		 		}
                    //the opponent
                    $sbv1 = "SELECT * FROM score WHERE ch_ip='$this->ch_ip' AND ip='$f'";
                    $sbq1 = $this->conn->query($sbv1);
                    $sba1 = mysqli_fetch_array($sbq1);
                    $sc_f = $sba1['Score'];
                    // this one is you
                    $sbv2 = "SELECT * FROM score WHERE ch_ip='$this->ch_ip' AND ip='$s'";
                    $sbq2 = $this->conn->query($sbv2);
                    $sba2 = mysqli_fetch_array($sbq2);
                    $sc_s = $sba2['Score'];

    		 		if($sc_s>$sc_f){
    		 			$this->conn->query("UPDATE result SET winner='$s' WHERE Cid='$this->ch_ip'");
    		 		}else if($sc_f>$sc_s){
    		 			$this->conn->query("UPDATE result SET winner='$f' WHERE Cid='$this->ch_ip'");
    		 		}else{
    		 			$this->conn->query("UPDATE result SET winner='IT IS A TIE!' WHERE Cid='$this->ch_ip'");
    		 		}
    		 	}else{
    		 		//you are the first
    		 		$insertr = "INSERT INTO result (Cid,first,second,winner) VALUES ('$this->ch_ip','$this->ip',0,0)";
    		 		$this->conn->query($insertr);
    		 	}
    		}
    	}		
    		include 'assets/html/questions.html';
	}	
}	
?>


