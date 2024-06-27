<?php
session_start();
require("../../php/db.php");
$valid = false;
$message = "Something went wrong!";

if(!isset($_SESSION['admin'])) {
    $username = input($_POST['username']);
    $password = input($_POST['password']);
    
    $res = db("select id, username, password, type from admin where username = '$username' && password = '$password'");
    if(mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        if($row['username'] === $username && $row['password'] === $password)
        {
            $valid = true;
            $_SESSION['admin'] = (object)["id" => $row['id'], "type" => $row['type']];
            $message = "Successfully Logged In";
        } else
        {
            $message = "Username or Password is Incorrect";
        }
    } else
    {
        $message = "Username or Password is Incorrect";
    }
}
response($valid, $message);
$_SESSION['response'] = $response; 
// echo json_encode($response);
$valid ? header("Location: ../") : header("Location: ../login.php");
?>