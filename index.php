<?php 

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
    default:
        include_once('./src/404.php');
}