<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require('./db.php');
require '../vendor/autoload.php';
require('./constant/constant.php');
require('./helper/generate_pdf.php');
$valid = false;
$message = "Something Went Wrong!";
$path = '../election.php';
if(isset($_SESSION['evoting_user']) && $_SESSION['evoting_user'] -> action == 'ELECTION')
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $north = $_POST['North'];
        $south = $_POST['South'];
        $west = $_POST['West'];
        $all_region = $_POST['All'];
        // $sss = $_POST['SSS'];
        $east = $_POST['East'];
        $west_chairmen = $_POST['West_chairmen'];
        if(count($north) == 4 && count($south) == 4 && count($west) == 4 && count($all_region) <= 8) 
        {
            $flag = 0;
            foreach($north as $n) {
                $res = db("select id from candidiate where id = '$n' && type='north'");
                if(mysqli_num_rows($res) === 0) {
                    $flag = 1;
                }
            }
            foreach($south as $s) {
                $res = db("select id from candidiate where id = '$s' && type='south'");
                if(mysqli_num_rows($res) === 0) {
                    $flag = 1;echo $flag;
                }
            }
            foreach($west as $w) {
                $res = db("select id from candidiate where id = '$w' && type='west'");
                if(mysqli_num_rows($res) === 0) {
                    $flag = 1;echo $flag;
                }
            }
            // foreach($sss as $ss) {
            //     $res = db("select id from candidiate where id = '$ss' && type='sss'");
            //     if(mysqli_num_rows($res) === 0) {
            //         $flag = 1;echo $flag;
            //     }
            // }
            // $All = array_merge($North, $South, $West, $East);
            foreach($all_region as $ar) {
                $res = db("select id from candidiate where id = '$ar' && type!='sss'");
                if(mysqli_num_rows($res) === 0 || in_array($ar, $north) || in_array($ar, $south) || in_array($ar, $west)) {
                    $flag = 1;echo $flag;
                }
            }
            if($flag === 0)
            {
                $res = db("select member_id from voting where member_id = '{$_SESSION['evoting_user']->id}'");
                if(mysqli_num_rows($res)  === 0)
                {
                    $north = trim(implode(",", $north), ",");
                    $south = trim(implode(",", $south), ",");
                    $west = trim(implode(",", $west), ",");
                    $east = trim(implode(",", $east), ",");
                    $west_chairmen = trim(implode(",", $west_chairmen), ",");
                    $all_region = trim(implode(",", $all_region), ",");
                    // $sql= "insert into voting (member_id, north, south, west, sss, all_region) values('{$_SESSION['evoting_user']->id}', '$north', '$south', '$west', '$sss', '$all_region')";
                    // echo $sql ; die;
                    $res = db("insert into voting (member_id, north, south, west, east, all_region,west_chairmen) values('{$_SESSION['evoting_user']->id}', '$north', '$south', '$west', '$east', '$all_region','$west_chairmen')");
                    if($res)
                    {
                        $_SESSION['thankyou'] = $last_id;
                        $valid = true;
                        $message ="You have Successfully Voted!";
                        db("update voting_members set vote = 1 where id = '{$_SESSION['evoting_user']->id}'");
                        $res = db("select member_id,email from voting_members where id = '{$_SESSION['evoting_user']->id}'");
                        $row = mysqli_fetch_assoc($res);
                        sendMail($row['email'], $row['member_id']);
                        sendMail('scrutineer@acma.co.in', $row['member_id']);
                        unset($_SESSION['evoting_user']);
                        $path = '../thankyou.php';
                    }
                } else
                {
                    $message = "You have already given a vote!";
                    unset($_SESSION['evoting_user']);
                    $path = '../login.php';
                }
            } else
            {
                $message = "Pls vote according to the guidelines!";
            }
        } else
        {
            $message = "Pls vote according to the guidelines!";
        }
    }
} else
{
    $path = '../login.php';
}
response($valid, $message);
$_SESSION['response'] = $response;
header("Location: {$path}");
// echo json_encode($response) 
?>