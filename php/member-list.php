<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
}
require '../../php/db.php';
$type = '';
$type = isset($_GET['type']) && in_array($_GET['type'], [1,0]) && $_GET['type'] != 'All' ? input($_GET['type']) : '';
$query = $type != '' ? "where vote = '$type'" : '';

$data = [];
$res = db("select id, name, email, company, member_id, vote, phone, designation from voting_members $query order by id desc");
while($row = mysqli_fetch_assoc($res)) {
    $row['s_no'] = ++$count;
    $data[] = $row;
}
echo json_encode($data);
?>