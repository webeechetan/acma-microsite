<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("Location: ./login.php");
    $response = ["success" => false, "message" => "You are not logged in!"];
    echo json_encode($response);
    die();
}

require_once '../../php/db.php';

$valid = false;
$message = "Something went wrong!";
if($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $name = input($_POST['name']);
    $email = input($_POST['email']);
    $company = input($_POST['company']);
    $designation = input($_POST['designation']);
    $member_id = input($_POST['member_id']);
    $id = input($_POST['id']);
    if(!isset($_POST['name']) || $_POST['name'] === '' || !isset($_POST['email']) || $_POST['email'] === '' || !isset($_POST['company']) || $_POST['company'] === '' || !isset($_POST['designation']) || $_POST['designation'] === '')
    {
        $message = "Pls fill the necessary field";
    } else
    {
        if($id == '' || $id == 0)
        {
            $res = db("select member_id from voting_members where member_id = '$member_id'");
            if(mysqli_num_rows($res) === 0 && $member_id !== '')
            {
                $res = db("select email from voting_members where email = '$email'");
                if($email == '' || mysqli_num_rows($res) > 0)
                {
                    $message = "Email Id already exist!";
                } else
                {
                    $updated_on = date('Y-m-d');
                    $res = db("insert into voting_members (name, email, company, designation, member_id, updated_on) values('$name', '$email', '$company', '$designation', '$member_id', '$updated_on')");
                    if($res)
                    {
                        $valid = true;
                        $message = "Successfully added";
                    } else
                    {
                        $message = "Error while insertion!";
                    }
                }
            } else
            {
                $message = "Member Id already exist!";
            }
        } else 
        {
            $res = db("select member_id from voting_members where id = '$id'");
            if(mysqli_num_rows($res) != 1)
            {
                $message = "Invalid member!";
            } else
            {
                $res = db("select member_id from voting_members where member_id = '$member_id' && id != '$id'");
                if(mysqli_num_rows($res) === 0 && $member_id !== '')
                {
                    $res = db("select email from voting_members where email = '$email' && id != '$id'");
                    if($email == '' || mysqli_num_rows($res) > 0)
                    {
                        $message = "Email Id already exist!";
                    } else
                    {
                        $updated_on = date('Y-m-d');
                        $res = db("update voting_members set name='$name', email='$email', company='$company', designation='$designation', member_id='$member_id', updated_on='$updated_on' where id='$id'");
                        if($res)
                        {
                            $valid = true;
                            $message = "Successfully updated";
                        } else
                        {
                            $message = "Error while insertion!";
                        }
                    }
                } else
                {
                    $message = "Member Id already exist1!";
                }
            }
        }
    }
} else
{
    $message = "Invalid request method";
}
response($valid, $message);
echo json_encode($response);
?>