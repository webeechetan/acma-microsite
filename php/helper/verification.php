<?php
class Verfication {
    public function sendEmail($useremail) {
        $otp = rand(1000,9999);
        // $otp = '1234';
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("acma@acma.in", "ACMA");
        $email->setSubject("OTP");
        $email->addTo($useremail, "Company");
        $email->addContent(
            "text/html", "Your One Time Password is <strong>{$otp}</strong>"
        );
        $sendgrid = new \SendGrid(constant('MAIL_PASSWORD'));
        try {
            $response = $sendgrid->send($email);
            return $response->statusCode() === 202 ? $otp : false;
        } catch (Exception $e) {
            // echo 'Caught exception: '. $e->getMessage() ."\n";
            return false;
        }
    }

    public function sendMobileOTP($phone) {
        $otp = rand(1000,9999);
        $apiKey = urlencode(constant('TEXTLOCAL_APIKEY'));
        $sender = urlencode(constant('TEXTLOCAL_SENDER'));
        $message = rawurlencode("Your One Time Password (OTP) for mobile number verification is $otp- Automotive Component Manufacturers Association of India");
        $data = array('apikey' => $apiKey, 'numbers' => $phone, "sender" => $sender, "message" => $message);//, "test" => true
        
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);
        $resfrom = json_decode($resp);
        // print_r($resfrom);
        // die();
        return $resfrom -> status == 'success' ? $otp : false;
    }
}
?>