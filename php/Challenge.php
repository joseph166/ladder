<?php
	class Challenge {
		public $conn;
		public $sender;
		public $receiver;
		public $sel;
		public $date;

		public function __construct($conn,$sender,$receiver,$sel,$date){
			$this->conn = $conn;
			$this->sender= $sender;
			$this->receiver = $receiver;
			$this->sel = $sel;
			$this->date = $date;
		}

		public function Set_challenge(){
			$qq = "SELECT Qid FROM question WHERE type='$this->sel' ORDER BY rand() LIMIT 3";
			$qr = $this->conn->query($qq);
			$qarr = array();
			while($qus = $qr->fetch_assoc()){
				 array_push($qarr, $qus['Qid']);
			}
			$ch_ip = random_int(1, 1321);
			$chk_if_me = "SELECT * FROM challenge WHERE ch_ip='$ch_ip'";
			$co = $this->conn->query($chk_if_me);
			if($co->num_rows > 0){
				$ch_ip = random_int(12, 5811);
			}
			// check if the guy doesn't exits.
			$kc = "SELECT * FROM user WHERE ip='$this->receiver'";
			$kcq = $this->conn->query($kc);
			if($kcq->num_rows > 0){
				if($this->sender==$this->receiver){
					return $this->sender;
				}else{
				$ins = "INSERT INTO challenge (ch_ip,sender,receiver,question1,question2,question3,set_date) VALUES ('$ch_ip','$this->sender','$this->receiver','$qarr[0]','$qarr[1]','$qarr[2]',$this->date)";
				if($this->conn->query($ins)){
					return $ch_ip;
				}else{
					return null;
				}
			}
			}else{
				return 'not_found';
			}

	}
}
include 'assets/html/challenge.html';
?>