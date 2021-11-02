<?php
	$ladder = "SELECT * FROM user";
	$lq = $conn->query($ladder);
	include 'assets/html/ladder.html';
?>