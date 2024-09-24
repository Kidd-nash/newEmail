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

    public function editingPost()
    {
        $editingId = $_GET["editingId"] ?? null;

        echo "Editing Post id:" . $editingId;

        $postQuery = $this->connection->prepare('SELECT * FROM post_a_note WHERE id = :editId');

        $postQuery->execute(['editId' => $editingId]);

        $post = $postQuery->fetch(PDO::FETCH_ASSOC);

        $editing_post = $post["content"];

        ob_start();

        include_once("./src/edit-class.php");

        return ob_get_clean();
    }

    public function updatingPost() 
    {
        session_start();
        ob_start();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST["id"] ?? null;
            $content = $_POST["content"] . '- edited' ?? null;
            
            $userQuery = $this->connection->prepare("UPDATE post_a_note SET content = :content WHERE id = :id");
            
            $userQuery->execute(['content' => $content, 'id' => $id]);

            $_SESSION['saved'] = true;

        }
        ob_end_clean();
        header("Location: http://email.api:8080/new-home");
        die();
    }

    public function deletingPost() 
    {
        ob_start();

        $deleteId = $_GET["deleteId"] ?? null;

        echo "Deleting Post id:" . $deleteId;

        $userQuery = $this->connection->prepare("DELETE FROM post_a_note WHERE id = :deleteId");

        $userQuery->execute(['deleteId' => $deleteId]);

        ob_end_clean();

        header("Location: http://email.api:8080/new-home");
        die();
    }

    public function allPosts() 
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
        
        $query = '
            SELECT
                p.id AS id,
                p.content AS content,
                p.date_posted AS date_posted,
                c.comment_content AS comment_content,
                c.id AS comment_id,
                c.date_posted AS date_posted,
                c.author_id AS author_id
            FROM
                post_a_note p
            LEFT JOIN
                post_a_note_comments c
            ON
                (p.id = c.post_id)
            ORDER BY p.id DESC
            LIMIT 10
            ;
        ';

        $postQuery = $this->connection->prepare($query);


        $array = [];

        $array[] = ['apple'];


        $array = [];


        $postQuery->execute([
            // 'author_id' => $author_id,
        ]);

        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);

        $formattedPosts = [];
        foreach ($posts as $post) {
            if (!isset($formattedPosts[$post['id']])) {
                $formattedPosts[$post['id']] = [];
            }
            if (!isset($formattedPosts[$post['id']]['comments'])) {
                $formattedPosts[$post['id']]['comments'] = [];
            }

            if (!empty($post['comment_id'])) {
                $formattedPosts[$post['id']]['comments'][] = [
                    'id' => $post['comment_id'],
                    'content' => $post['comment_content'],
                    'date_posted' => $post['date_posted'],
                    'author_id' => $post['author_id']
                ];
            }

            $formattedPosts[$post['id']]['content'] = $post['content'];
            $formattedPosts[$post['id']]['id'] = $post['id'];
            $formattedPosts[$post['id']]['date_posted'] = $post['date_posted'];
            

        }

        // echo '<pre>' . print_r($formattedPosts, true) . '</pre>';

        // echo '<pre>' . print_r($posts, true) . '</pre>';
        // die('d');


        ob_start();

        include_once('./src/new-all-posts.php');

        return ob_get_clean();
    }

    public function commentPost() 
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

            $post_id = $_POST['post-id'];
            $id_plus = $_SESSION['userId'];

            $comment = $_POST['comment'];
            $author_id = $id_plus;

            $userQuery = $this->connection->prepare(
                'INSERT INTO post_a_note_comments (comment_content, date_posted, author_id, post_id) 
                VALUES (:comment, :date_posted, :author_id, :post_id)'
            );
            $userQuery->execute([
                'comment' => $comment, 
                'date_posted' => date('Y-m-d'), 
                'author_id' => $author_id,
                'post_id' => $post_id
            ]);

            $_SESSION['saved'] = true;

            // $_SESSION['saved_comment_id'] = $commentId;
        }
        ob_end_clean();

        header("Location: http://email.api:8080/all-posts");
        die();

    }

}
// post_a_note
// post_a_note_comments


