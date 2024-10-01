<?php
session_start();
unset($_SESSION['userId']);
unset($_SESSION['username']);
unset($_SESSION['profile_pic']);

header("Location: http://email.api:8080/home");
die();