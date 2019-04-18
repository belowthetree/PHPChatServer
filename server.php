<?php

$servername = "localhost";
$username = "root";
$password = "94a33e25c56f327d";
$dbname = "ChatServer";
$peername = $_POST['username'];
$peerpassword = $_POST['password'];
$type = $_POST['type'];
$time = $_POST['time'];

$conn = mysqli_connect($servername, $username, $password, $dbname);
if(!$conn){
    die("连接数据库失败".mysqli_connect_error());
}
if(!isset($_COOKIE[$peername])||$type=="login"){
    $comm = "select * from users where username='".$peername."' and password='".$peerpassword."'";
    $result = mysqli_query($conn, $comm);
    if(mysqli_num_rows($result) == 0){
        die($_COOKIE[$peername]."用户名或者密码错误"."select * from users where username='".$peername."' and password='".$peerpassword."'");
    }
    else
    {
      setcookie($peername, time(), time()+3600);
      echo "ok";
    }
}


if($type=="send"){
    $comm = "INSERT INTO ChatText (text, frm, t, time) VALUES ('".$_POST['text']."', '".$peername."', 'server', '".$time."')";
    if(!mysqli_query($conn, $comm))
      echo mysqli_error($conn)."send";
}
else if($type=="rec"){
    $comm = "select text, frm from ChatText WHERE time >='".$time."'";
    setcookie($peername, time(), time()+3600);
    $result = mysqli_query($conn, $comm);
    if(!$result)
      die(mysqli_error($conn)."rec");
  
    while($row = mysqli_fetch_assoc($result)){
      if($row['frm']!=$peername)
        echo "<div style='float:left;'>".$row['frm'].":</div><br/>"."<div class='chatbox rounded-lg text-white' style='background-color: #00CCFF;width:60%;padding: 15px;'>
        ".$row['text']."
        </div><br/><br/><br/>";
      else
        echo "<div style='float:right;'>".$row['frm'].":</div><br/>"."<div class='chatbox rounded-lg bg-primary text-white' style='width:60%;padding: 15px;float:right'>
        ".$row['text']."
        </div><br/><br/><br/>";
    }
}

?>