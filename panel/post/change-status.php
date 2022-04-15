<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';
session_start();
if (!$_SESSION['user'])
{
    redirect('auth/login.php');
}

global $pdo;
$sql = "SELECT * FROM posts WHERE id = ?";
$statement = $pdo->prepare($sql);
$statement->execute([$_GET['post_id']]);
$post = $statement->fetch();
if (!$post)
{
    redirect('panel/post');
}

$sql = "UPDATE posts SET status = ? WHERE id = ?";
$status = $post->status == 10 ? 0 : 10 ;
$statement  = $pdo->prepare($sql);
$statement->execute([$status,$_GET['post_id']]);
redirect('panel/post');