<?php

// include_once('./src/connection.php');

namespace Root\NewEmail;

use \Pdo;

class Post {

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

    public function createPost() 
    {
        return "creating";
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
        // $postQuery = $this->connection->prepare('SELECT * FROM post_a_note WHERE author_id = :author_id');

        // $postQuery->execute([
        //     'author_id' => 798,
        // ]);
        ob_start();
        
        // include_once(__DIR__ . '/home-class.php');

        include_once('./src/home-class.php');

        // return "retrieving " . print_r($posts, true);

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