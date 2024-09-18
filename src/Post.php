<?php

namespace Root\NewEmail;

use \PDO;

class Post {

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

    public function createPost() 
    {
        session_start();

        ob_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            session_start();

            $isLoggedIn = false;
            if (isset($_SESSION['userId'])) {
                $isLoggedIn = true;
            } else {
                $_SESSION['accessDeniedError'];
                ob_end_clean();
                header("Location: http://email.api:8080/new-home");
                die();
            }

            $id_plus = $_SESSION['userId'];

            $content = $_POST['content'];
            $author_id = $id_plus;

            $userQuery = $this->connection->prepare('INSERT INTO post_a_note (content, date_posted, author_id) VALUES (:content, :date_posted, :author_id)');
            $userQuery->execute(['content' => $content, 'date_posted' => date('Y-m-d'), 'author_id' => $author_id]);

            $_SESSION['saved'] = true;
        }
        ob_end_clean();

        header("Location: http://email.api:8080/new-home");
        die();
    }

    public function listPost()
    {

        session_start();

        $isLoggedIn = false;
        if (isset($_SESSION['userId'])) {
            $isLoggedIn = true;
            $id_plus = $_SESSION['userId'];
            if (isset($_SESSION['saved'])) {
                unset($_SESSION['saved']);
                $isSaved = true;
            }
        } else {
            $id_plus = 0;
        }

        $author_id = $id_plus;
        
        $postQuery = $this->connection->prepare('SELECT * FROM post_a_note WHERE author_id = :author_id');

        $postQuery->execute([
            'author_id' => $author_id,
        ]);

        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);

        ob_start();

        include_once('./src/home-class.php');

        return ob_get_clean();
    }

    public function updatingPost() 
    {
        return "updating";
    }

    public function deletingPost() 
    {
        return "deleting";
    }

}