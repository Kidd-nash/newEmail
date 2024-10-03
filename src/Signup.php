<?php

namespace Root\NewEmail;

use \PDO;

class Signup {

    protected $connection;

    public function __construct()
    {

                $hostname = 'db_postgres_lab'; //get host name

                $dbname = 'first'; // Set the database name
        
                $username = 'pguser'; // Set the username with permissions to the database
        
                $password = 'pgpwd'; // Set the password with permissions to the database
        
                $dsn = "pgsql:host=$hostname;dbname=$dbname"; // Create the DSN (data source name) by combining the database type (PostgreSQL), hostname and dbname
        
                $this->connection = new PDO($dsn, $username, $password); //Create PDO

    }

    public function registrationForm() {

        ob_start();

        include_once('./src/register-class.php');

        return ob_get_clean();
    }

    public function submitRegistration() {
        ob_start();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = $_POST['email'];
            $user_name = $_POST['user-name'];
            $password = $_POST['password'];
            $hash_password = md5($password);

            
            $userQuery = $this->connection->prepare('INSERT INTO email_users (email, username, password) VALUES (:email, :user_name, :hash_password)');

            $userQuery->execute(['email' => $email, 'user_name' => $user_name, 'hash_password' => $hash_password]);

        }
        $this->sendEmail($email, $user_name);

        ob_end_clean();
        ob_start();

        include_once("./src/registered-class.php");

        return ob_get_clean();
    }

    protected function sendEmail($email, $user_name) 
    {
        include_once('./src/email.php');

        $emailTo = $email;
        $emailFrom = 'register@email.com';
        $subject = 'Registration';
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

            <b>HI, ' . $user_name . ' you have registered</b>
            <br>
            <p>Please click the button to verify your account</p>
            <br>
            <a href="http://email.api:8080/verify" target="_self">Verify</a>
            <br>
            <p>Thank you,</p>
            <p>Email Posts Team</p>
        </body>
        </html>
        ';

        sendEmail($emailTo, $emailFrom, $subject, $content);

    }

    public function loginForm() {

        ob_start();

        include_once('./src/login-class.php');

        return ob_get_clean();
    }

    public function submitLogin() {

        session_start();
        ob_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
            $user_name_or_email = $_POST["userNameOrEmail"];
            $password = $_POST["password"];
            $hash_password = md5($password);
        
        
            $userQuery = $this->connection->prepare('SELECT * FROM email_users WHERE (username = :user_name_or_email OR email = :user_name_or_email) AND password = :hash_password');
        
            $userQuery->execute([
                'user_name_or_email' => $user_name_or_email,
                'hash_password' => $hash_password
            ]);
        
            $user = $userQuery->fetch(PDO::FETCH_ASSOC);

            $userId = $user['id'] ?? null;
        
            if (!is_null($userId)) {
                $_SESSION['userId'] = $userId;
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile_pic'] = !empty($user['pfp_path'])
                    ? Post::UPLOADS_DIR . $user['pfp_path']
                    : 'uploads/default.jpg'
                ;

                ob_end_clean();
                header("Location: http://email.api:8080/home-page");
                die();
            }
        }
        die('test');

        return ob_get_clean();
    }
    
}