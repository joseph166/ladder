<?php
	
	final class connection{

		private static $instance = null;
		private static $connection;

		public static function getInstance(){
			if (is_null(self::$instance)){
				self::$instance = new Connection();
			}
			return self:: $instance;
		}

		private function __construct(){}
		private function __clone(){}
		private function __wakeup(){}

		public static function connect($host,$user,$pwd,$dbname){
			self::$connection = mysqli_connect($host,$user,$pwd,$dbname);
		}

		public static function getConnection(){
			return self::$connection;
		}
	}

?>