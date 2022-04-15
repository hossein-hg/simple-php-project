<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';
session_start();
if (!$_SESSION['user'])
{
    redirect('auth/login.php');
}
global $pdo;
if (isset($_GET['category_id']))
{
$sql = "SELECT * FROM categories WHERE id = ?";
$statement = $pdo->prepare($sql);
$statement->execute([$_GET['category_id']]);
$category = $statement->fetch();

    if ($category)
    {
        $sql = "DELETE FROM categories where id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute([$_GET['category_id']]);

    }
}

redirect('panel/category');
