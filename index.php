<?php

require("vendor/autoload.php");

use Forum\Controllers\ForumController;

$controller = new ForumController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");
    if ($controller->validatePost($username, $email, $message, $errors))
        $controller->addPost($username, $email, $message);
    $controller->index($errors);
} else{
    $controller->index();
}
