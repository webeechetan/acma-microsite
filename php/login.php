<?php
session_start();
require('./db.php');
require '../vendor/autoload.php';
require './constant/constant.php';
require './helper/verification.php';

$valid = false;
$message = "Something Went Wrong!";
$path = '../';
// $date = date('Y-m-d');
// $end_date = '2021-07-27';
if ($_SERVER["REQUEST_METHOD"] == "POST" ) 
{
    // Authenticate
    if(!isset($_SESSION['evoting_user']))
    {
        
        $email = input($_POST['email']);
        $res = db("select id, email from voting_members where email = '$email'");  
        if(mysqli_num_rows($res) === 1) 
        {
            $row = mysqli_fetch_assoc($res);
            $check = db("select id from voting where member_id = '{$row['id']}'");
            if(mysqli_num_rows($check) === 0)
            {
                if($row['email'] === $email) 
                {
                    $sendMail = new Verfication();
                    // $otp = $sendMail -> sendEmail($email);
                    $otp = true;
                    if($otp) 
                    {
                        $valid = true;
                        // $message = "OTP has been sent to your registered email id!";
                        $message = "Validate Mobile Number";
                        $evoting_user = new stdClass;

                        
                        $evoting_user -> id = $row['id'];
                        $evoting_user -> email = $row['email'];
                        $evoting_user -> login_time = date('Y-m-d h:i:s a');
                        
                        $evoting_user -> email_otp = $otp;
                        $evoting_user -> email_otp_verified = date('Y-m-d h:i:s a');
                        
                        $evoting_user -> action = 'VERIFY_MOBILE';
                        // $evoting_user -> action = 'VERIFY_EMAIL_OTP';

                        $_SESSION['evoting_user'] = $evoting_user;
                    } else
                    {
                        $message = "Unable to send OTP! Try again";
                    }
                } else 
                {
                    $message = "Email is incorrect!";
                }
            } else 
            {
                $message = "You have already voted!";
            }
        } else 
        {
            $message = "Email is not registered with us!";
        }
    } else 
    {
        $path = '../member-details.php';
        if($_SESSION['evoting_user'] -> action === 'VERIFY_EMAIL_OTP')
        {
            $path = '../';
            $email_otp = input($_POST['email_otp']);
            /*
            || EMAIL OTP VERIFY
            ===================*/
            $evoting_user = $_SESSION['evoting_user'];

            $submit_date = new DateTime(date('Y-m-d h:i:s a'));
            $login_time = new DateTime($evoting_user -> login_time);
            $expire = $submit_date->diff($login_time);
            if($expire->format('%h') == 0 && $expire->format('%i') <= 5)
            {
                if($email_otp == $evoting_user->email_otp)//$evoting_user->email_otp
                {
                    $valid = true;
                    $message = "OTP Verified";
                    $evoting_user -> email_otp_verified = date('Y-m-d h:i:s a');
                    $evoting_user -> action = 'VERIFY_MOBILE';
                    $_SESSION['evoting_user'] = $evoting_user;
                } else
                {
                    $message = "Incorrect OTP";
                }
            } else
            {
                unset($_SESSION['evoting_user']);
                $message = "OTP Expired! Pls Re-Send OTP";
            }
        } else if($_SESSION['evoting_user'] -> action === 'VERIFY_MOBILE')
        {
            if(!(isset($_SESSION['evoting_user'] -> phone))) 
            {
                /*
                || SEND MOBILE OTP
                ===================*/
                if(isset($_POST['phone']))
                {
                    $phone = input($_POST['phone']);
                    $sendMail = new Verfication();
                    $otp = $sendMail -> sendMobileOTP($phone);
                    if($otp)
                    {
                        $valid = true;
                        $message = "OTP has been sent to your mobile number!";
                        $_SESSION['evoting_user'] -> phone = $phone;
                        $_SESSION['evoting_user'] -> mobile_otp = $otp;
                    } else
                    {
                        $message = "Unable to send otp. Pls try again later!";
                    }
                } else 
                {
                    $message = "Fill mobile number first!";
                }
            } else
            {
                if(isset($_POST['resend']))
                {
                    // unset($_SESSION['evoting_user'] -> mobile_otp);
                    /*
                    || RE-SEND MOBILE OTP
                    ===================*/
                    if($_SESSION['evoting_user'] -> phone === '' || $_SESSION['evoting_user'] -> phone === null)
                    {
                        unset($_SESSION['evoting_user']);
                        $message = "Some issue with mobile OTP! Try again";
                    } else
                    {
                        $sendMail = new Verfication();
                        $otp = $sendMail -> sendMobileOTP($_SESSION['evoting_user'] -> phone);
                        if($otp)
                        {
                            $valid = true;
                            $message = "OTP has been sent to your mobile number!";
                            $_SESSION['evoting_user'] -> mobile_otp = $otp;
                        } else
                        {
                            $message = "Unable to send otp. Pls try again later!";
                        }
                    }
                } else
                {
                    /*
                    || VERIFY MOBILE OTP
                    ===================*/
                    if(isset($_POST['mobile_otp']))
                    {
                        $mobile_otp = input($_POST['mobile_otp']);
                        if($mobile_otp == $_SESSION['evoting_user']->mobile_otp)
                        {
                            $valid = true;
                            $message = "OTP Verified";
                            $_SESSION['evoting_user'] -> mobile_otp_verified = date('Y-m-d h:i:s a');
                            $_SESSION['evoting_user'] -> action = 'UPDATE_PHONE';
                        } else
                        {
                            $message = "Incorrect OTP";
                        }
                    } else
                    {
                        $message = "Fill OTP first!";
                    }
                }
            }
        } else if($_SESSION['evoting_user'] -> action === 'UPDATE_PHONE')
        {
            if($_SESSION['evoting_user']->phone !== '')
            {
                $res = db("update voting_members set phone = '{$_SESSION['evoting_user']->phone}' where id = '{$_SESSION['evoting_user']->id}'");
                if($res) 
                {
                    $valid = true;
                    $message = "Welcome to ACMA EC elections. Your mobile number updated.";
                    $_SESSION['evoting_user'] -> action = 'ELECTION';
                    $path = '../election.php';
                }
            }
        }
    }
} else 
{
    $message = "ELECTION TO THE ACMA EXECUTIVE COMMITTEE 2022-2023 Has Ended";
}
response($valid, $message);
$_SESSION['response'] = $response;
header("Location: {$path}");
// echo json_encode($response)
?>