<?php

namespace Forum\Controllers;

use Forum\databaseHelpers\DBWrapper;
use Forum\Models;
use Forum\Views\ForumView;

class ForumController
{
    private $postDB;
    public function __construct()
    {
        $this->postDB = new DBWrapper("Post");
    }

    public function index(array $validationErrors = [])
    {
        $posts = $this->postDB->selectAll("date", true);

        $counts = $this->postDB->selectCount("email");
        $emailCounts = [];

        foreach($counts as $row){
            $emailCounts[strtolower($row["email"])] = $row["count"];
        }

        foreach($posts as &$post){
            $post["emailPostCount"] = $emailCounts[strtolower($post["email"])];
        }

        (new ForumView())->render($posts, $validationErrors);

    }
    public function addPost(string $username, string $email, string $message)
    {
        $postModel = new Models\PostModel($username, date("Y-m-d H:i:s"), $message, $email);
        $this->postDB->insert($postModel);
    }

    public function validatePost(string $username, string $email, string $message, &$errors) : bool {
        $errors = [];
        preg_match("/^([a-zA-Z]*|([a-zA-Z]+\.[a-zA-Z]+)*)@([a-zA-Z]*|([a-zA-Z]+\.[a-zA-Z]+)*)\.[a-zA-Z]*$/" ,$email,$matches);
        if (empty($matches))
            $errors["email"] = "Invalid email format";
        else if (strlen($email) == 0 || strlen($email) > 45)
            $errors["email"] = "Email must be between 1 to 45 characters.";

        if (strlen($username) == 0 || strlen($username) > 45)
            $errors["username"] = "Username must be between 1 to 45 characters.";

        if (strlen($message) == 0 || strlen($message) > 1000)
            $errors["message"] = "Message must be between 1 to 1000 characters.";

        return empty($errors);
    }
}