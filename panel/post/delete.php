<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';
session_start();
if (!$_SESSION['user'])
{
    redirect('auth/login.php');
}

global $pdo;
if (isset($_GET['post_id']))
{
    $sql = "SELECT * FROM posts WHERE id = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$_GET['post_id']]);
    $post = $statement->fetch();
    if (!$post)
    {
        redirect('panel/post');
    }
    $baseUrl = dirname(dirname(__DIR__));
    if (file_exists($baseUrl.$post->image))
    {
        unlink($baseUrl.$post->image);
    }
    $sql = "DELETE FROM posts WHERE id = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$_GET['post_id']]);
    redirect('panel/post');
}