<?php 

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


switch ($uri) {
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
    case '/connection';
        include_once('./src/connection.php');
        break;
    default:
        include_once('./src/404.php');
}