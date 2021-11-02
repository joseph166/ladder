<?php 

require_once 'connection.php';
connection::connect('localhost','root','','ladder');
$conn = connection::getInstance();
$conn = $conn->getConnection(); 

if($_SERVER['REQUEST_METHOD']=="POST"){
    $user = mysqli_real_escape_string($conn,w3($_POST['user']));
    $pwd = mysqli_real_escape_string($conn,w3($_POST['pwd']));
    if(strlen($user)>50||strlen($user)==0||strlen($pwd)>50||strlen($pwd)==0){
        echo "<div class='alert-info p-2 text-center'><b>Invalid</b></div>";
    }else{
        $login = "SELECT * FROM user WHERE username='$user'";
        $log_res = $conn->query($login);
        if($log_res->num_rows>0){
            while($data = $log_res->fetch_assoc()){
                $a = $data['password'];
                $b = $data['name'];
                $c = $data['lname'];
                $d = $data['Uid'];
                $z = $data['username'];
                $e = $data['ip'];
            }
            if(password_verify($pwd, $a)){
                require 'assets/config.php';
                $_SESSION['Uid']=$d;
                $_SESSION['name']=$b;
                $_SESSION['user']=$z;
                $_SESSION['lname']=$c;
                $_SESSION['ip']=$e;
                header("Location: index.php?section=Home");
            }else{
            echo "<div class='alert-danger p-1 text-center'><b>Password Incorrect!</b></div>";
            }
        }else{
            echo "<div class='alert-secondary p-1 text-center'><b>User does not exists.</b></div>";
        }
    }
}
function w3($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
include 'assets/html/nav.html';
?>
<div class="log">
<div class="container mt-4">
    <form action="login.php" method="POST" class="form p-1">
        <h4 class="text-light text-center">Login:  <strong class="icofont-login"></strong></h4>
        <label for="user">Username</label><br>
        <input id="user" class="form-control" type="text" name="user" placeholder="Enter your Username">
        <label for="pwd" class="mt-2">Password</label><br>
        <input id="pwd" class="form-control" type="password" name="pwd" placeholder="Enter your Password">
        <div class="mt-2">
            <button name="submit" class="btn btn-light">Enter</button>
            <a class="btn btn-light" href="sign.php">Sign up</a>
        </div>
    </form>
</div>
</div></div>
</body>
</html>
