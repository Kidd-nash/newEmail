<?php

namespace Root\NewEmail;

use \PDO;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Post
{

    protected $connection;

    public const UPLOADS_DIR = 'uploads/';

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
                header("Location: http://email.api:8080/home-page");
                die();
            }

            $id_plus = $_SESSION['userId'];

            $content = $_POST['content'];
            $author_id = $id_plus;


            $fileDetails = $this->handleFileUpload();
            $target_file = $fileDetails['target_file'];


            $userQuery = $this->connection->prepare(
                'INSERT INTO post_a_note (content, date_posted, author_id, img_path) VALUES (:content, :date_posted, :author_id, :img_path)'
            );

            $userQuery->execute([
                'content' => $content,
                'date_posted' => date('Y-m-d'),
                'author_id' => $author_id,
                'img_path' => $target_file
            ]);

            $_SESSION['saved'] = true;
        }
        ob_end_clean();

        header("Location: http://email.api:8080/home-page");
        die();
    }

    // home-page
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


        $query = '
            SELECT
                p.id AS id,
                p.content AS content,
                p.date_posted AS date_posted,
                p.upvotes AS upvotes,
                p.author_id AS author_id,
                p.img_path AS img_path,
                c.comment_content AS comment_content,
                c.id AS comment_id,
                c.date_posted AS comment_date_posted,
                c.author_id AS comment_author_id
            FROM
                post_a_note p
            LEFT JOIN
                post_a_note_comments c
            ON
                (p.id = c.post_id)
            WHERE
                p.author_id = :author_id
            ORDER BY p.date_posted DESC
            ;
        ';


        $postQuery = $this->connection->prepare($query);
        $postQuery->execute([
            'author_id' => $author_id,
        ]);

        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);
        $formattedPosts = $this->getFormattedPosts($posts);

        // echo '<pre>' . print_r($formattedPosts, true) . '</pre>';
        // die();
        ob_start();

        include_once('./src/pages/home-page.php');

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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST["id"] ?? null;
            $content = $_POST["content"] . '- edited' ?? null;

            $userQuery = $this->connection->prepare("UPDATE post_a_note SET content = :content WHERE id = :id");

            $userQuery->execute(['content' => $content, 'id' => $id]);

            $_SESSION['saved'] = true;
        }
        ob_end_clean();
        header("Location: http://email.api:8080/home-page");
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

        header("Location: http://email.api:8080/home-page");
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
                p.upvotes AS upvotes,
                p.author_id AS author_id,
                c.comment_content AS comment_content,
                c.id AS comment_id,
                c.date_posted AS comment_date_posted,
                c.author_id AS comment_author_id
            FROM
                post_a_note p
            LEFT JOIN
                post_a_note_comments c
            ON
                (p.id = c.post_id)
            ORDER BY p.id DESC
            LIMIT 20
            ;
        ';

        $postQuery = $this->connection->prepare($query);

        $postQuery->execute([
            // 'author_id' => $author_id,
        ]);

        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);
        $formattedPosts = $this->getFormattedPosts($posts);

        ob_start();

        include_once('./src/pages/home-page.php');

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
                header("Location: http://email.api:8080/home-page");
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
        }
        ob_end_clean();

        header("Location: http://email.api:8080/all-posts");
        die();
    }

    public function upVoting()
    {
        session_start();

        if (!isset($_SESSION['userId'])) {
            echo "Access Denied: You must be logged in to upvote.";
            return;
        }

        $userId = $_SESSION['userId'];
        $upvoteId = $_GET['upvoteId'] ?? null;

        if ($upvoteId === null) {
            echo "Invalid post ID.";
            return;
        }

        $selectUserQuery = $this->connection->prepare(
            'SELECT * FROM user_like_post WHERE userid = :userid AND postid = :postid'
        );

        $selectUserQuery->execute([
            'userid' => $userId,
            'postid' => $upvoteId
        ]);

        $accountLiked = $selectUserQuery->fetch(PDO::FETCH_ASSOC);

        if (!$accountLiked) {
            $userLikeQuery = $this->connection->prepare(
                'INSERT INTO user_like_post (userid, postid) VALUES (:userid, :postid)'
            );

            $userLikeQuery->execute([
                'userid' => $userId,
                'postid' => $upvoteId
            ]);

            // echo "Post liked!";
            $isLiked = true;
        } else {
            // echo "You have already liked this post.";
            $isAlreadyLiked = true;
        }

        $getPostCount = $this->connection->prepare(
            'SELECT COUNT(*) FROM user_like_post WHERE postId = :postId'
        );

        $getPostCount->execute([
            'postId' => $upvoteId
        ]);

        $likeCount = $getPostCount->fetch(PDO::FETCH_ASSOC);

        $updatePostQuery = $this->connection->prepare(
            'UPDATE post_a_note SET upvotes = :upvotes  WHERE id = :id'
        );

        $updatePostQuery->execute([
            'upvotes' => $likeCount['count'],
            'id' => $upvoteId
        ]);

        return $likeCount['count'];
    }

    public function downloadPdf()
    {
        $dompdf = new Dompdf();

        $postQuery = $this->connection->prepare('SELECT * FROM post_a_note');

        $postQuery->execute([
            // 'author_id' => $author_id,
        ]);

        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);



        ob_start();
        include_once('./src/pdfDownloadTpl.php');
        $output = ob_get_clean();

        // die($output);

        $dompdf->loadHtml($output);

        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();
    }

    public function spreadSheetDownload()
    {
        session_start();
        ob_start();
        $userId = $_SESSION['userId'];


        $postQuery = $this->connection->prepare('SELECT * FROM post_a_note WHERE author_id = :author_id');

        $postQuery->execute([
            'author_id' => $userId
        ]);

        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);

        $selectEmailQuery = $this->connection->prepare('SELECT * FROM email_users WHERE id = :author_id');

        $selectEmailQuery->execute([
            'author_id' => $userId
        ]);

        $emailUser = $selectEmailQuery->fetch(PDO::FETCH_ASSOC);

        $email = $emailUser['email'];

        $user_name = $emailUser['username'];

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'Date Posted');
        $activeWorksheet->setCellValue('B1', 'Content');
        $activeWorksheet->setCellValue('C1', 'Upvotes');

        $a_value = 1;

        foreach ($posts as $post) {
            $a_value++;
            $activeWorksheet->setCellValue('A' . $a_value, $post['date_posted']);
            $activeWorksheet->setCellValue('B' . $a_value, $post['content']);
            $activeWorksheet->setCellValue('C' . $a_value, $post['upvotes']);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'emailposts-' . date('H-i-s') . '.xlsx';
        $writer->save($fileName);

        $fullPath = "/var/www/public/newEmail/$fileName";


        $this->emailSSAttach($email, $user_name, $fullPath);
        ob_end_clean();


        chmod($fullPath, 0755);
    }

    public function loadCsv()
    {
        session_start();
        ob_start();

        $userId = $_SESSION['userId'];


        $postQuery = $this->connection->prepare(
            'SELECT * FROM post_a_note WHERE author_id = :author_id
        '
        );

        $postQuery->execute([
            'author_id' => $userId
        ]);
        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);

        echo '"Date Posted", "Contents", "Upvotes"' . PHP_EOL;
        foreach ($posts as $post) {
            echo '"' . $post['date_posted'] . '", "' . $post['content'] . '", "' . $post['upvotes'] . '"' . PHP_EOL; //End of line
        };

        $output = ob_get_clean();

        header('Content-Description: File Transfer');
        header('Content-Type: text/csv'); // application/xlsx  -> text/csv
        header('Content-Disposition: attachment; filename="text-test.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        // header('Content-Length: ' . filesize($file));
        // readfile($file);
        echo $output;
        exit;

        exit;
    }

    protected function emailSSAttach($email, $user_name, $fullPath)
    {

        include_once('./src/email.php');
        $emailTo = $email;
        $emailFrom = 'EmailPostTeam@email.com';
        $subject = 'Change Password';
        $name = $user_name;
        $content = '
        <html>
        <head>
        </head>
        <body>
            <h1>Data </h1>

            <b>Hi, ' . $name . ' this email contains an attach file of your data, presented in spreasheet.</b>
            <br>
            <p>Click the file to view your data.</p>
            <br>
            <br>
            <p>Thank you,</p>
            <p>Email Posts Team</p>
        </body>
        </html>
        ';

        sendEmail($emailTo, $emailFrom, $subject, $content, $fullPath);
    }

    public function loadCsvFile()
    {
        session_start();
        ob_start();

        $userId = $_SESSION['userId'];
        $postQuery = $this->connection->prepare('SELECT * FROM post_a_note WHERE author_id = :author_id');
        $postQuery->execute([
            'author_id' => $userId
        ]);
        $posts = $postQuery->fetchAll(PDO::FETCH_ASSOC);

        $fileName = 'text-test-text.csv';
        $fullPath = "/var/www/public/newEmail/$fileName";

        $fp = fopen($fullPath, 'w');
        fputcsv($fp, ['Date', 'Content', 'Upvotes']);
        foreach ($posts as $post) {
            fputcsv($fp, [
                $post['date_posted'],
                $post['content'],
                $post['upvotes']
            ]);
        };
        fclose($fp);

        $output = ob_get_clean();

        header('Content-Description: File Transfer');
        header('Content-Type: text/csv'); // application/xlsx  -> text/csv
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        echo $output;

        exit;
    }

    public function cropImage()
    {
        $im = imagecreatefromjpeg('uploads/mario.jpeg');
        $size = min(imagesx($im), imagesy($im));
        $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
        if ($im2 !== FALSE) {
            imagepng($im2, 'uploads/example-cropped-into-folder.png');
            imagedestroy($im2);
        }
        imagedestroy($im);
    }

    public function uploadImage()
    {
        global $target_file;
        $target_dir = "uploads/";
        $target_file = self::UPLOADS_DIR . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (isset($_POST["submit"])) { // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        if (file_exists($target_file)) { // Check if file already exists
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        if ($_FILES["fileToUpload"]["size"] > 1500000000) { // Check file size
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" // Allow certain file formats
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) { // Check if $uploadOk is set to 0 by an error
            echo "Sorry, your file was not uploaded.";
        } else { // if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function uploadAndCrop()
    {
        ob_start();
        session_start();

        $fileDetails = $this->handleFileUpload();
        $extension = $fileDetails['extension'];
        $newFileName = $fileDetails['newFileName'];
        $target_file = $fileDetails['target_file'];


        if ($extension == 'png') {

            $im = imagecreatefrompng($target_file);
        } elseif ($extension == 'jpg' || $extension == 'jpeg') {

            $im = imagecreatefromjpeg($target_file);
        }

        $size = 300;

        $imageWidth = imagesx($im);
        $imageLength = imagesy($im);

        $im2 = imagecrop($im, ['x' => ($imageWidth - $size) / 2, 'y' => ($imageLength - $size) / 2, 'width' => $size, 'height' => $size]);
        if ($im2 !== FALSE) {
            imagepng($im2, 'uploads/' . $newFileName . '.png');
            imagedestroy($im2);
        }
        imagedestroy($im);



        $userId = $_SESSION['userId'];

        $accountQuery = $this->connection->prepare(
            'UPDATE email_users SET pfp_path = :pfp_path WHERE id = :id'
        );

        $accountQuery->execute([
            'pfp_path' => $newFileName . '.png',
            'id' => $userId
        ]);

        $_SESSION['profile_pic'] = self::UPLOADS_DIR . $newFileName . '.png';

        ob_end_clean();
        header("Location: http://email.api:8080/home-page");
        die();
    }

    public function searchPosts()
    {
        session_start();

        $isLoggedIn = false;
        if (isset($_SESSION['userId'])) {
            $isLoggedIn = true;
        } else {
        }

        ob_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $search = $_POST['search'];

            $searchQuery = $this->connection->prepare(
                "SELECT * FROM post_a_note WHERE author_id = :id AND content LIKE :searchTerm"
            );
            $searchQuery->execute([
                'id' => $_SESSION['userId'],
                'searchTerm' => '%' . $search . '%'
            ]);

            $searched = $searchQuery->fetchAll(PDO::FETCH_ASSOC);
            $formattedPosts = $this->getFormattedPosts($searched);

            include_once('./src/pages/home-page.php');

            return ob_get_clean();
        }
    }

    private function getFormattedPosts($posts): array
    {

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
            $formattedPosts[$post['id']]['upvotes'] = $post['upvotes'];
            $formattedPosts[$post['id']]['author_id'] = $post['author_id'];
            $formattedPosts[$post['id']]['img_path'] = $post['img_path'];
        }

        return $formattedPosts;
    }

    private function handleFileUpload(): array
    {
        $targetFile = '';

        if (!empty($_FILES["fileToUpload"]["name"])) {
            $extension = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
            $newFileName = md5(date('Y-m-d H:i:s'))  . rand(111, 999) . rand(111, 999);
            $target_file = self::UPLOADS_DIR . $newFileName . '.' . $extension;

            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (isset($_POST["submit"])) { // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }

            if (file_exists($target_file)) { // Check if file already exists
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            if ($_FILES["fileToUpload"]["size"] > 1500000000) { // Allow certain file formats
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" // Allow certain file formats
                && $imageFileType != "gif"
            ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) { // Check if $uploadOk is set to 0 by an error
                echo "Sorry, your file was not uploaded."; // if everything is ok, try to upload file  
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        return [
            'target_file' => $target_file,
            'extension' => $extension,
            'newFileName' => $newFileName
        ];
    }
}
