<?php
function sendMail($useremail, $member_id) {
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("acma@acma.in", "ACMA");
    $email->setSubject("ELECTION TO THE ACMA EXECUTIVE COMMITTEE 2022-2023 [{$member_id}]");
    // $email->isHTML(true);
    $email->addTo($useremail, "Company");
    ob_start();
    include('../thankyou_emailer.php');
    $email->addContent("text/html", ob_get_contents());
    $sendgrid = new \SendGrid(constant('MAIL_PASSWORD'));
    $response = $sendgrid->send($email);
    ob_end_clean();
}

?>