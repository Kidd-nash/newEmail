<?php

ob_start();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once('./src/email.php');
   
    $emailFrom = $_POST['email'];
    $message = $_POST['message'];

    $emailTo = 'admin@email.api';
    $subject = 'customer contact message';
    $content = "
    <html>

    <head>
    </head>
    <body>
        <p>
            Message from customer: 
        </p>
        <p>
            $message
        </p>
    </body>
    </html>
    ";

    sendEmail($emailTo, $emailFrom, $subject, $content);


}
ob_end_clean();
