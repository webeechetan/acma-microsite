<?php
$conn = mysqli_connect("localhost", "root", "", "acma_micro_site");

if($conn -> connect_errno){
    echo "connection error";
    exit();
}
date_default_timezone_set("Asia/Calcutta");
$last_id;
function input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data=  addslashes($data);
  return $data;
}

function input_decode($str) {
    $clear = strip_tags($str);
    $clear = html_entity_decode($clear);
    $clear = urldecode($clear);
    $clear = preg_replace('/ +/', ' ', $clear);
    $clear = trim($clear);
    return $clear;
}

function db($sql){
    global $conn, $last_id, $response;
    $res = $conn -> query($sql);
    $last_id = $conn -> insert_id;
    mysqli_set_charset( $conn, 'utf8');
    // $conn->close();
    return $res;
}

function response($success, $message) {
    global $response;
    $response['success'] = $success;
    $response['message'] = $message;
}
?>