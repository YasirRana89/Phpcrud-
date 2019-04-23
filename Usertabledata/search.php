<?php
$id="";
$uname="";
$email="";
$password="";
if(isset($_POST["Find"])){
$hostname="localhost";
$dbname="user";
$username="root";
$password="";

$conn=new PDO("mysql:host=$hostname; dbname=$dbname" ,$username,$password);
if (isset($_POST['uname'])){
$query="SELECT id,uname,email,password FROM users WHERE uname=:uname";
var_dump($query);
$result=$conn->prepare($query);
$executerecord=$result=execute(array(":uname"=>$uname));
if($executerecord){
     if($result->rowCount()> 0){
         foreach($result as $row){
             $id=$row['id'];
             $uname=$row['uname'];
             $email=$row['email'];
             $password=$row['password'];
         }
     }else{
         echo"no record found";
     }

}

}
}


?>