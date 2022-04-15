<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';
session_start();
if (!$_SESSION['user'])
{
    redirect('auth/login.php');
}

global $pdo;
$sql = "SELECT * FROM categories";
$statement = $pdo->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll();

if (isset($_POST['title']) && $_POST['title'] !=='' &&
    isset($_POST['body']) && $_POST['body'] !=='' &&
    isset($_POST['category_id']) && $_POST['category_id'] !=='' &&
    isset($_FILES['image']) && $_FILES['image']['name'] !=='')
{

    $sql = "INSERT INTO posts SET title = ? , body = ? , category_id = ? , image = ?";
    $baseUrl1 = dirname(dirname(__DIR__));

    $imageMime = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);

    $allowedMimes = ['png','jpg','jpeg','gif'];
    if (!in_array($imageMime,$allowedMimes))
    {
        redirect('panel/post');
    }
    $imageName = "\assets\images\posts\\".date('Y-m-d-h-m-s').".".$imageMime; // اسلش یا بک اسلش فرقی نمی کند ویندوز بک اسلش هست
//    $imageName = DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."posts".DIRECTORY_SEPARATOR.date('Y-m-d-h-m-s').".".$imageMime;

    move_uploaded_file($_FILES['image']['tmp_name'],$baseUrl1.$imageName);

    $statement = $pdo->prepare($sql);
    $statement->execute([$_POST['title'],$_POST['body'],$_POST['category_id'],$imageName]);
    redirect('panel/post');

}
else{

    if (!empty($_POST) and $_POST['title'] === '')
    {

        $errorTitle = 'فیلد عنوان الزامی است';
    }
    if (!empty($_POST) and $_POST['body'] === '')
    {
        $errorBody = 'فیلد توضیحات الزامی است';
    }
    if (!empty($_POST) and $_FILES['image']['name'] === '')
    {
        $errorImage = 'فیلد فایل الزامی است';
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP panel</title>
    <link rel="stylesheet" href="<?= asset('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" media="all" type="text/css">
</head>
<body>
<section id="app">
    <?php require_once '../layouts/top-nav.php'?>
    <section class="container-fluid">
        <section class="row">
            <section class="col-md-2 p-0">
                <?php require_once '../layouts/sidebar.php'?>
            </section>
            <section class="col-md-10 pt-3">

                <form action="create.php" method="post" enctype="multipart/form-data">
                    <section class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="title ...">
                        <?php if (isset($errorTitle)){ ?>
                            <span class="text-danger"><?= $errorTitle ?></span>
                        <?php } ?>
                    </section>
                    <section class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" name="image" id="image">
                        <?php if (isset($errorImage)){ ?>
                            <span class="text-danger"><?= $errorImage ?></span>
                        <?php } ?>
                    </section>
                    <section class="form-group">
                        <label for="cat_id">Category</label>
                        <select class="form-control" name="category_id" id="cat_id">
                            <?php foreach ($categories as $category){ ?>
                            <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php } ?>
                        </select>
                    </section>
                    <section class="form-group">
                        <label for="body">Body</label>
                        <textarea class="form-control" name="body" id="body" rows="5" placeholder="body ..."></textarea>
                        <?php if (isset($errorBody)){ ?>
                            <span class="text-danger"><?= $errorBody ?></span>
                        <?php } ?>
                    </section>
                    <section class="form-group">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </section>
                </form>

            </section>
        </section>
    </section>

</section>

<script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
<script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>
</html>