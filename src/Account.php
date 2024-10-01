<?php

namespace Root\NewEmail;

use \PDO;

class Account {

    protected $connection;

    public function __construct()
    {

                $hostname = 'db_postgres_lab'; 

                $dbname = 'first'; 
        
                $username = 'pguser'; 
        
                $password = 'pgpwd'; 
        
                $dsn = "pgsql:host=$hostname;dbname=$dbname"; 
        
                $this->connection = new PDO($dsn, $username, $password); 

    }

    public function changePasswordEmail() 
    {
        session_start();
        ob_start();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = $_POST['email']; 
            $dateRequest = date_create('now');
            
            $userQuery = $this->connection->prepare('SELECT * FROM email_users WHERE email = :email');

            $userQuery->execute([
                'email' => $email,
            ]);

            $user = $userQuery->fetch(PDO::FETCH_ASSOC);

            $userId = $user['id'] ?? null;

            $user_name = $user['username'] ?? null;

            if (!is_null($userId)) {
                $_SESSION['userId'] = $userId;
                $_SESSION['username'] = $user_name;
                ob_end_clean();
            }

            $hashed = md5($email . date('Y-m-d h:i:s'));

            $secondQuery = $this->connection->prepare(
                'INSERT INTO change_pass_users (email, user_id, hashed, date) VALUES (:email, :user_id, :hashed, :date)'
            );

            $secondQuery->execute([
                'email' => $email,
                'user_id' => $userId,
                'hashed' => $hashed,
                'date' => $dateRequest->format('Y-m-d')
            ]);

        }
        
        $this->encodedEmail($email, $user_name, $hashed);
        ob_end_clean();

        ob_start();
        echo "email sent";
        return ob_get_clean();

    }

    protected function encodedEmail($email, $user_name, $hashed) 
    {
        include_once('./src/email.php');

        $emailTo = $email;
        $hashedLink = $hashed;
        $emailFrom = 'EmailPostTeam@email.com';
        $subject = 'Change Password';
        $name = $user_name;
        $content = '
        <html>
        <head>
            <style>
                a:link, a:visited {
                background-color: rgb(115, 194, 251);
                color: white;
                padding: 14px 25px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                border-radius: 5;
                }
                
                a:hover, a:active {
                background-color: rgb(0, 128, 255);
                }
            </style>
        </head>
        <body>
            <h1>Registration to Email Posts</h1>

            <b>Hi, ' . $name . ' this email will redirect you to change your password, if you did not authorize this please ignore this message.</b>
            <br>
            <p>Please click the button to change your account password</p>
            <br>
            <a href="http://email.api:8080/change-password?hsah=' . $hashedLink . '" target="_self">Click here</a>
            <br>
            <p>Thank you,</p>
            <p>Email Posts Team</p>
        </body>
        </html>
        ';

        sendEmail($emailTo, $emailFrom, $subject, $content);

    }

    public function changePasswordUpdate() 
    {
        session_start();

        $hash = $_GET['hsah'];
        
        $queryGetId = $this->connection->prepare(
            'SELECT * FROM change_pass_users WHERE hashed = :hashed'
        );

        $dateToday = date_create('now');

        $queryGetId->execute([
            'hashed' => $hash
        ]);

        $user = $queryGetId->fetch(PDO::FETCH_ASSOC);

        $date = $user['date'];

        $dateCompare = date_diff(date_create($date), $dateToday);

        $dateDifference = intval($dateCompare->format('%a'));

        if ($dateDifference > 1) {
            ob_start();
                include_once('./src/change-pass-error.php');
            return ob_get_clean();
        }

        $userId = $user['user_id'] ?? null;

        if (!is_null($userId)) {
            $_SESSION['userId'] = $userId;
        }
        
        include_once('change-password.php');


    }

    public function changePasswordUpdating() 
    {
        session_start();
        if($_SERVER['REQUEST_METHOD'] == 'POST') { 

                if (empty($_POST['password'])) {
                    ob_start();
                    $emptyPasswordError = true;
                    return ob_get_clean();
                }

                $hashed = md5($_POST['password']);

                $changePassQuery = $this->connection->prepare(
                    'UPDATE email_users SET password = :password WHERE id = :id'
                );

                $changePassQuery->execute([
                    'password' => $hashed,
                    'id' => $_SESSION['userId']
                ]);
                ob_start();
                // success template
                return ob_get_clean();
            }

    }

// http://email.api/email-change-password?hash=fdsiuovkfslafyiudo


//   $email = $_POST['email']
//   $hash = md5( $email . date('Y-m-d H:i:s') )
//   query database, select id from email_users WHERE email = '$email'
//   $result = pg fetch assoc
//    $id = $result['id']

// database table columns:   change_password -> table name
//    user_id
//    created_at - leave blank for now, or do not use
//    hash

// sve user-id, hash to database

// ##############
// change password form

//   query database, select * from change_password where hash = 'hfdsufiodshf'
//    $user = fetch assoc
///    $_SESSION['userId'] = $user['id']

// #############
// change password submit

//  update query   : update email_users SET password = md5($_post['new_password']) WHERE id = :user_id
//   execute->('user_id' => $_session['userId']



// add column for date, 
// email submiited on forgot password, 
//  in hash table, set date = today
// $date = date('Y-m-d')  , sept 20

// change password form
// check that date is not older than 1 day  sept 24 click link => invvalid
// check validity of link

}

// $date = date_create("2024-09-21");
// $dateTwo = date_create("2024-09-23");
// $dateDiff = date_diff($date, $dateTwo);
// $difference = intval($dateDiff->format('%a'));

// echo $difference;