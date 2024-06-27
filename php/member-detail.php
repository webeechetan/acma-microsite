<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
}
require '../../php/db.php';
$valid = false;
$message = "Something went wrong!";

$id = input($_GET['id']);
$res = db("select id, name, email, company, member_id, designation from voting_members where id = '$id'");
if(mysqli_num_rows($res) === 1)
{
    $valid = true;
    $message = "Data Fetched From Server";
    $response['data'] = mysqli_fetch_assoc($res);
}
response($valid, $message);
echo json_encode($response);
?>