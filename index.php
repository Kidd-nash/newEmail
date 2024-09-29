<?php

use Symfony\Component\HttpFoundation\Request;


$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Acme\\Test\\', __DIR__);

$uri = strtok($_SERVER["REQUEST_URI"], '?');


use Root\NewEmail\Post;
use Root\NewEmail\Signup;
use Root\NewEmail\Account;

$post = new Post();
$signup = new Signup();
$account = new Account();




switch ($uri) {
    // case '/posts/create':
    //     echo $post->createPost();
    //     break;
    // case '/posts/retrieve':
    //     echo $post->listPost();
    //     break;
    case '/posts/edit':
        echo $post->updatingPost();
        break;
    case '/posts/delete':
        echo $post->deletingPost();
        break;

    
    case '/new-home':
        echo $post->listPost();
        break;
    case '/new-register':
        include_once('./src/register-class.php');
        break;
    case '/new-registering':
        echo $signup->submitRegistration();
        break;
    case '/new-login':
        include_once('./src/login-class.php');
        break;
    case '/new-loggingin':
        echo $signup->submitLogin();
        break;
    case '/new-posting':
        echo $post->createPost();
        break;
    case '/class-post-delete':
        echo $post->deletingPost();
        break;
    case '/class-post-editing':
        echo $post->editingPost();
        break;
    case '/class-post-updating':
        echo $post->updatingPost();
        break;
    // case '/class-post-upvoting':
    //     echo $post->upVote();
    //     break;
    case '/class-post-upvote':
        echo $post->upVoting();
        break;
    case '/forgot-password':
        include_once('./src/forgot-password.php');
        break;
    case '/email-change-password':
        echo $account->changePasswordEmail();
        break;
    case '/change-password':
        echo $account->changePasswordUpdate();
        break;
    case '/change-password-updating':
        echo $account->changePasswordUpdating();
        break;
    case '/new-logout':
        include_once('./src/new-logout.php');
        break;
    case '/all-posts':
        echo $post->allPosts();
        break;
    case '/comment-post':
        echo $post->commentPost();
        break;
    case '/download-pdf':
        echo $post->downloadPdf();
        break;
    case '/download-xlsx':
        $post->spreadSheetDownload();
        break;
    case '/download-csv':
        $post->loadCsv();
        break;
    case '/download-fcsv':
        $post->loadCsvFile();
        break;

    

    
    case '/home':
        include_once('./src/home.php');
        break;
    case '/new':
        include_once('./src/new.php');
        break;
    case '/contact':
        include_once('./src/contact.php');
        break;
    case '/contact-submit':
        include_once('./src/contact-submit.php');
        break;
    case '/register':
        include_once('./src/register.php');
        break;
    case '/login':
        include_once('./src/login.php');
        break;
    case '/registering':
        include_once('./src/registering.php');
        break;
    case '/logging-in':
        include_once('./src/logging-in.php');
        break;
    case '/connection':
        include_once('./src/connection.php');
        break;
    case '/autoload-php':
        include_once('./vendor/autoload.php');
        break;
    case '/verify':
        include_once('./src/verifying.php');
        break;
    case '/posting':
        include_once('./src/posting.php');
        break;
    case '/post-delete':
        include_once('./src/post-delete.php');
        break;
    case '/post-editing':
        include_once('./src/post-editing.php');
        break;
    case '/log-out':
        include_once('./src/logout.php');
        break;
    case '/post-updating':
        include_once('./src/post-updating.php');
        break;
    case '/trials':
        include_once('./src/trials/trials.php');
        break;
    case '/trial':
        include_once('./src/trials/trial.php');
        break;
    case '/submit':
        include_once('./src/trials/submit.php');
        // include_once('./src/trials/trial.php');
        break;
    default:
        include_once('./src/404.php');
}