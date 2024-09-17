<?php

use Symfony\Component\HttpFoundation\Request;


$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Acme\\Test\\', __DIR__);

$uri = strtok($_SERVER["REQUEST_URI"], '?');

// $fullUri  = $_SERVER['REQUEST_URI'];
// $uriElements = explode('?', $fullUri);
// $uri = $uriElements[0];


//c
//r
//u
//d
use Root\NewEmail\Message;

$message = new Message();
// $message->echoHello();
// die('test');


switch ($uri) {
    case '/message/list':
        echo $message->doList();
        break;
    case '/message/create':
        echo $message->doCreate();
        break;
    case '/message/submit':
        echo $message->handleSubmit();
        break;

    case '/home':
        include_once('./src/home.php');
        break;
    case '/new':
        include_once('./src/new.php');
        break;
    case '/contact';
        include_once('./src/contact.php');
        break;
    case '/contact-submit';
        include_once('./src/contact-submit.php');
        break;
    case '/register';
        include_once('./src/register.php');
        break;
    case '/login';
        include_once('./src/login.php');
        break;
    case '/registering';
        include_once('./src/registering.php');
        break;
    case '/logging-in';
        include_once('./src/logging-in.php');
        break;
    case '/connection';
        include_once('./src/connection.php');
        break;
    case '/autoload-php';
        include_once('./vendor/autoload.php');
        break;
    case '/verify';
        include_once('./src/verifying.php');
        break;
    case '/posting';
        include_once('./src/posting.php');
        break;
    case '/post-delete';
        include_once('./src/post-delete.php');
        break;
    case '/post-editing';
        include_once('./src/post-editing.php');
        break;
    case '/log-out';
        include_once('./src/logout.php');
        break;
    case '/post-updating';
        include_once('./src/post-updating.php');
        break;
    case '/trials';
        include_once('./src/trials/trials.php');
        break;
    case '/trial';
        include_once('./src/trials/trial.php');
        break;
    case '/submit';
        include_once('./src/trials/submit.php');
        // include_once('./src/trials/trial.php');
        break;
    default:
        include_once('./src/404.php');
}